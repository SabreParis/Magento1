<?php
/**
 * Created : 2015
 * 
 * @category Ayaline
 * @package Sabre_Adminhtml
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
require_once Mage::getModuleDir('controllers', 'Mage_Adminhtml') . DS . 'Sales' . DS . 'OrderController.php';

class Sabre_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{

    public function exportOrderAction()
    {
        $id = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($id);

        if (!$order->getId()) {
            $this->_getSession()->addError($this->__('This order no longer exists.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return false;
        }

        try {
            $order->setData('erp_status', Sabre_Dataflow_Model_Export_Sales_Order::ORDER_ERP_STATUS_FORCE);
            $order->getResource()->saveAttribute($order, 'erp_status');

            /* @var $cronSchedule Mage_Cron_Model_Schedule */
            $cronSchedule = Mage::getModel('cron/schedule');

            $scheduledAt = (new Zend_Date(null, null, 
                Mage::app()->getLocale()->getLocale()))
                ->setSecond(0)
                ->addMinute(1)
                ->toString(Zend_Date::ISO_8601);
            
            $cronSchedule
                ->setJobCode('sabre_dataflow_export_sales_order')
                ->setStatus(Mage_Cron_Model_Schedule::STATUS_PENDING)
                ->setCreatedAt(now())
                ->setScheduledAt($scheduledAt)//$scheduledAt
            ;
            $cronSchedule->save();

            $this->_getSession()->addSuccess($this->__('Export request has been registered.'));
        } catch (Exception $exc) {
            $this->_getSession()->addError($this->__('An error has occurred.'));
            Mage::logException($exc);
        }

        $this->_redirectReferer();
    }
}
