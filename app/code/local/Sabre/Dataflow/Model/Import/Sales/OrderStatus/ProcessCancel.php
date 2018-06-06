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
class Sabre_Dataflow_Model_Import_Sales_OrderStatus_ProcessCancel extends Sabre_Dataflow_Model_Import_Sales_OrderStatus_AbstractProcess
{

    protected $_defaultTabLevel = 3;

    /**
     * 
     * @param Mage_Sales_Model_Order $order
     * @param boolean $checkShipment
     */
    public function process($order)
    {
        $this->_resetProcessTrace();

        // validating order before processing
        $this->_addTrace("Processing to cancel order ...");
        $this->_addTrace("Validating order informations");
        $this->_validate($order);
        $this->_addTrace("Ok", 1);

        $this->_addTrace("Cancel order ...");
        $order->cancel();
        $this->_addTrace("Ok", 1);

        $this->_addTrace("Save order ...");
        $order->save();
        $this->_addTrace("Ok", 1);

        // FORCE STATE
        if ($order->getState() != Mage_Sales_Model_Order::STATE_CANCELED) {
            $this->_addTrace("Order state forcing change ...");
            $order->setState(Mage_Sales_Model_Order::STATE_CANCELED);
            $order->getResource()->saveAttribute($order, 'state');
            $this->_addTrace("state : {$order->getState()}", 1);
        }
    }

    /**
     * Validating order before processing
     * 
     * SPEC : La commande ne doit pas être déjà facturée, livrée ou annulée dans Magento.
     * Uniquement les commandes payées par chèque et virement (donc autre que par CB)
     * 
     * @param Mage_Sales_Model_Order $order
     * @throws type
     */
    protected function _validate($order)
    {
        /**
         * Check if order exists
         */
        if (!$order->getId()) {
            Mage::throwException('The order does not exists.');
        }

        /**
         * Check if can cancel
         */
        $this->_addTrace("Check if order can be canceled ?", 1);
        if (!$order->canCancel()) {
            Mage::throwException('The order cannot be canceled.');
        }
        $this->_addTrace("Yes", 2);

        /**
         * Check if order has invoices
         */
        $this->_addTrace("Check if order has Invoices ?", 1);
        if ($order->hasInvoices()) {
            Mage::throwException('The order has invoice(s).');
        }
        $this->_addTrace("No", 2);
    }
}
