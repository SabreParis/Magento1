<?php

/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Sabre_Dataflow
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Dataflow_Model_Api extends Mage_Api_Model_Resource_Abstract
{

    public function exportOrders()
    {
        $jobCode = "sabre_dataflow_export_sales_order";
        $this->__addToScheduler($jobCode);
        return true;
    }

    public function importProducts() {
        $jobCode = "sabre_dataflow_import_catalog_products";
        $this->__addToScheduler($jobCode);
        return true;
    }

    public function importPricing() {
        $jobCode = "sabre_dataflow_import_pricing";
        $this->__addToScheduler($jobCode);
        return true;
    }

    public function importInventory() {
        $jobCode = "sabre_dataflow_import_inventory";
        $this->__addToScheduler($jobCode);
        return true;
    }

    public function importShops() {
        $jobCode = "sabre_dataflow_import_shops";
        $this->__addToScheduler($jobCode);
    }

    public function updateOrderStatus($args) {

        $orderIncrementId = $args->incrementId;
        $status = $args->status;

        $order = Mage::getModel('sales/order')->load($orderIncrementId, 'increment_id');
        if (!$order || !$order->getId()) {
            $this->_fault('order_not_exists', "Order #$orderIncrementId not found");
        }

        $processModel = null;
        switch (strtolower($status)) {
            case "processing" : {
                $processModel = Mage::getSingleton('sabre_dataflow/import_sales_orderStatus_processInvoice');
                break;
            }
            case "complete" : {
                $processModel = Mage::getSingleton('sabre_dataflow/import_sales_orderStatus_processShipment');
                break;
            }
            case "canceled" : {
                $processModel = Mage::getSingleton('sabre_dataflow/import_sales_orderStatus_processCancel');
                break;
            }
            default: {
                $this->_fault('status_not_exists', "invalid status '$status'");
            }
        }

        try {
            $processModel->process($order);
        } catch (Exception $e) {
            $this->_fault("processing_error", $e->getMessage());
        }

        return $orderIncrementId;

    }

    /**
     * @param $jobCode
     * @throws Exception
     */
    private function __addToScheduler($jobCode)
    {
        /* @var $cronSchedule Mage_Cron_Model_Schedule */
        $cronSchedule = Mage::getModel('cron/schedule');

        $scheduledAt = (new Zend_Date(null, null, Mage::app()->getLocale()->getLocale()))
            ->setSecond(0)
            ->addMinute(1)
            ->toString(Zend_Date::ISO_8601);

        $cronSchedule
            ->setJobCode($jobCode)
            ->setStatus(Mage_Cron_Model_Schedule::STATUS_PENDING)
            ->setCreatedAt(now())
            ->setScheduledAt($scheduledAt)//$scheduledAt
        ;
        $cronSchedule->save();
    }

}
