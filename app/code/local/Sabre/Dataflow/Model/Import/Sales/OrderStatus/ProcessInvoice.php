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
class Sabre_Dataflow_Model_Import_Sales_OrderStatus_ProcessInvoice extends Sabre_Dataflow_Model_Import_Sales_OrderStatus_AbstractProcess
{

    protected $_defaultTabLevel = 3;

    /**
     * 
     * @param Mage_Sales_Model_Order $order
     * @param boolean $checkShipment
     */
    public function process($order, $checkShipment = true)
    {
        $this->_resetProcessTrace();

        // validating order before processing
        $this->_addTrace("Processing to invoice order ...");
        $this->_addTrace("Validating order informations");
        $this->_validate($order);
        $this->_addTrace("Ok", 1);
        /**
         * Check if order has shipment
         */
        if ($checkShipment) {
            $this->_addTrace("Checking if order has shipment(s) ?");
            if ($order->hasShipments()) {
                Mage::throwException('The order has shipment(s).');
            }
            $this->_addTrace("No", 1);
        }

        $this->_addTrace("Init invoice ...");

        // init invoice
        $invoice = $this->_initInvoice($order);
        $this->_addTrace("Ok", 1);

        // Register invoice
        $this->_addTrace("Register invoice ...");
        $invoice->register();
        $this->_addTrace("Ok", 1);

        // remove
        $invoice->setEmailSent(true);

        $invoice->getOrder()->setCustomerNoteNotify(true);
        $invoice->getOrder()->setIsInProcess(true);

        $this->_addTrace("Customer Note Notify");
        $this->_addTrace("Order Set Is In Process");

        // Saving transaction
        $this->_addTrace("Resource transaction save ...");
        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($invoice)
            ->addObject($invoice->getOrder());
        $transactionSave->save();
        $this->_addTrace("Ok", 1);


        // Sending mail
        $this->_addTrace("Send invoice mail");
        $invoice->sendEmail();
        $this->_addTrace("Ok", 1);

        // FORCE STATE
        if ($invoice->getOrder()->getState() != Mage_Sales_Model_Order::STATE_PROCESSING) {
            $this->_addTrace("Order state forcing change ...");
            $invoice->getOrder()->setState(Mage_Sales_Model_Order::STATE_PROCESSING);
            $invoice->getOrder()->getResource()->saveAttribute($order, 'state');
            $this->_addTrace("state : {$invoice->getOrder()->getState()}", 1);
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
         * Check invoice create availability
         */
        $this->_addTrace("Order canInvoice ? ", 1);
        if (!$order->canInvoice()) {
            Mage::throwException('The order does not allow creating an invoice.');
        }
        $this->_addTrace("Yes", 2);

        /**
         * Check if order has shipment
         */
        $this->_addTrace("Check if order has shipment ? ", 1);
        if ($order->hasInvoices()) {
            Mage::throwException('The order has Invoices(s).');
        }
        $this->_addTrace("No", 2);

        /**
         * Check if allowed payment method
         */
        $this->_addTrace("Check order payment method {$order->getPayment()->getMethod()} is allowed ? ", 1);
        if (!in_array($order->getPayment()->getMethod(), $this->_allowedPaymentMethods())) {
            Mage::throwException("The order payment method {$order->getPayment()->getMethod()} is not allowed.");
        }
        $this->_addTrace("Yes", 2);
    }

    /**
     * Initialize invoice model instance
     *
     * @param $order Mage_Sales_Model_Order
     * @return Mage_Sales_Model_Order_Invoice
     */
    protected function _initInvoice($order)
    {

        /* @var $invoice Mage_Sales_Model_Order_Invoice */
        $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice(array());

        if (!$invoice->getTotalQty()) {
            Mage::throwException('Cannot create an invoice without products.');
        }

        return $invoice;
    }

    /**
     * Get requested items qty's from request
     * 
     * @param  Mage_Sales_Model_Order $order
     * @return array
     */
    protected function _getItemQtys($order)
    {
        $orderItems = $order->getAllVisibleItems();
        $data = array();

        /* @var $orderItem Mage_Sales_Model_Order_Item */
        foreach ($orderItems as $orderItem) {
            $_qty = (int) $orderItem->getQtyToInvoice();
            $data[$orderItem->getId()] = "$_qty";
        }

        return array('items' => $data);
    }

    /**
     * retrieve allowed payment methods
     * 
     * @return array
     */
    protected function _allowedPaymentMethods()
    {
        /**
         * @todo Add array in config
         */
        return array(
            'banktransfer',
            'checkmo',
        );
    }
}
