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
class Sabre_Dataflow_Model_Export_Sales_Order_Observer
{

    public function runExport($observer)
    {
        try {
            $dataflow = Mage::getModel('sabre_dataflow/export_sales_order');
            $dataflow->execute(array('data_flow' => 'export/sales/order'));
        } catch (Exception $ex) {
            Mage::log($ex->getMessage(), Zend_Log::ERR);
            Mage::logException($ex);
        }

        return $this;
    }
}
