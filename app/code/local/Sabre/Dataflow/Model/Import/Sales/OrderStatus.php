<?php

/**
 * Created : 2015
 * 
 * @category Ayaline
 * @package Ayaline_XXXX
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Dataflow_Model_Import_Sales_OrderStatus extends Ayaline_DataflowManager_Model_Import_Abstract
{

    const NB_COLS = 2;

    /**
     * @var Varien_Io_File
     */
    protected $_io;

    /**
     * {@inheritdoc}
     */
    protected function _getDocumentation()
    {
        return <<<DOC
Import des status de commande
DOC;
    }

    /**
     * {@inheritdoc}
     */
    protected function _import($filename)
    {
        $separator = str_repeat('-', strlen(__METHOD__));
        $this->_log($separator);
        $this->_log(__METHOD__);

        $pathInfo = pathinfo($filename);

        $this->_io = new Varien_Io_File();
        $this->_io->open(array('path' => $pathInfo['dirname']));
        $this->_io->streamOpen($filename, 'r');
        $this->_io->streamLock(true);

        $nbRow = 0;
        while (($_row = $this->_io->streamReadCsv(',', '"'))) {
            $nbRow++;

            $_nbCols = count($_row);
            if ($_nbCols !== self::NB_COLS) {
                $_jsonRow = Mage::helper('core')->jsonEncode($_row);
                $this->_log("Row {$nbRow}, do not contains the required number of columns ({$_nbCols})\nRow => {$_jsonRow}", Zend_Log::ERR);
                continue;
            }

            $_rowTrim = array_map('trim', $_row);

            $this->_process($_rowTrim, $nbRow);
        }

        $this->_io->streamUnlock();
        $this->_io->streamClose();

        $this->_log($separator . "\n");
    }

    /**
     * Process import row
     * 
     * @param array $row
     * @param int $rowNum
     */
    protected function _process($row, $rowNum)
    {
        $this->_startProfiling("process_row");

        $this->_log("Process Row ID: {$rowNum}");

        $separator = str_repeat('_', strlen(__METHOD__));
        $this->_log($separator);
        $this->_log(__METHOD__);

        $rowIncrementId = $row[0];
        $rowStatus = $row[1];

        /* @var $order Mage_Sales_Model_Order */

        $this->_log("\tStart Process Order : '{$rowIncrementId}' ...");

        $order = $this->_loadOrderByIncId($rowIncrementId);
        if (!$order->getId()) {
            $this->_log("\t\tThe order : '$rowIncrementId' does not exists.");
            return;
        }

        $this->_log("\t\tProcess status: '{$rowStatus}'");
        try {
            switch (strtolower($rowStatus)) {
                case "processing" : {
                        $this->_processStateProcessing($order);
                        break;
                    }
                case "complete" : {
                        $this->_processStateComplete($order);
                        break;
                    }
                case "canceled" : {
                        $this->_processStateCanceled($order);
                        break;
                    }
                default: {
                        Mage::throwException("\t\tRow Status : '$rowStatus' is not allowed.");
                    }
            }
            // Do some checks after process complete
            $this->_postProcess($order, $rowStatus);
        } catch (Exception $ex) {
            $this->_log("\t\t\t{$ex->getMessage()}", Zend_Log::ERR);
            //$this->_logException($ex);
        }

        $this->_log("\tEnd Process Order : '{$rowIncrementId}'.");

        $this->_stopProfiling("process_row");
    }

    /**
     * Process to invoice order
     * 
     * @param Mage_Sales_Model_Order $order
     */
    protected function _processStateProcessing($order)
    {
        $this->_startProfiling(__FUNCTION__);

        /* @var $processModel Sabre_Dataflow_Model_Import_Sales_OrderStatus_AbstractProcess */
        $processModel = Mage::getSingleton('sabre_dataflow/import_sales_orderStatus_processInvoice');
        try {

            $processModel->process($order);
            $this->_processTraceLog($processModel);
        } catch (Exception $ex) {
            $this->_processTraceLog($processModel);
            $this->_stopProfiling(__FUNCTION__);
            throw $ex;
        }
        $this->_stopProfiling(__FUNCTION__);
    }

    /**
     * 
     * @param Mage_Sales_Model_Order $order
     */
    protected function _processStateComplete($order)
    {
        $this->_startProfiling(__FUNCTION__);
        /* @var $processModel Sabre_Dataflow_Model_Import_Sales_OrderStatus_AbstractProcess */
        $processModel = Mage::getSingleton('sabre_dataflow/import_sales_orderStatus_processShipment');
        try {

            $processModel->process($order);
            $this->_processTraceLog($processModel);
        } catch (Exception $ex) {
            $this->_processTraceLog($processModel);
            $this->_stopProfiling(__FUNCTION__);
            throw $ex;
        }
        $this->_stopProfiling(__FUNCTION__);
    }

    /**
     * 
     * @param Mage_Sales_Model_Order $order
     */
    protected function _processStateCanceled($order)
    {
        $this->_startProfiling(__FUNCTION__);
        /* @var $processModel Sabre_Dataflow_Model_Import_Sales_OrderStatus_AbstractProcess */
        $processModel = Mage::getSingleton('sabre_dataflow/import_sales_orderStatus_processCancel');
        try {

            $processModel->process($order);
            $this->_processTraceLog($processModel);
        } catch (Exception $ex) {
            $this->_processTraceLog($processModel);
            $this->_stopProfiling(__FUNCTION__);
            throw $ex;
        }
        $this->_stopProfiling(__FUNCTION__);
    }

    /**
     * 
     * @param Mage_Sales_Model_Order $order
     * @param string $rowStatus
     */
    protected function _postProcess($order, $rowStatus)
    {
        $mapMatchingStatusState = array(
            'processing' => Mage_Sales_Model_Order::STATE_PROCESSING,
            'complete' => Mage_Sales_Model_Order::STATE_COMPLETE,
            'canceled' => Mage_Sales_Model_Order::STATE_CANCELED,
        );

        if ($mapMatchingStatusState[$rowStatus] != $order->getState()) {
            Mage::throwException("\tOrder state should be : {$mapMatchingStatusState[$rowStatus]}");
        }

        /**
         * Put Others checks here
         */
        $this->_log("\t\tSuccess ending process ...");
        $this->_log("\t\t\t\tOrder state  : '{$order->getState()}'.");
        $this->_log("\t\t\t\tOrder status : '{$order->getStatus()}'.");
    }

    /**
     * 
     * @param Sabre_Dataflow_Model_Import_Sales_OrderStatus_AbstractProcess $processModel
     */
    protected function _processTraceLog($processModel)
    {
        $processTrace = $processModel->getProcessTrace();

        foreach ($processTrace as $item) {
            $this->_log($item);
        }
    }

    /**
     * 
     * @param type $rowIncrementId
     * @return Mage_Sales_Model_Order
     */
    protected function _loadOrderByIncId($rowIncrementId)
    {

        return Mage::getModel('sales/order')->load($rowIncrementId, 'increment_id');
    }

    /**
     * {@inheritdoc}
     */
    protected function _validate()
    {
        return true;
    }
}
