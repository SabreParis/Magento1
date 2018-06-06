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
class Sabre_Dataflow_Model_Import_Sales_OrderStatus_ProcessShipment extends Sabre_Dataflow_Model_Import_Sales_OrderStatus_AbstractProcess
{

    protected $_defaultTabLevel = 3;

    /**
     * 
     * SPEC :   tout livrer
     *          tout facturer (si cela n’a pas été fait au préalable)
     *          La commande doit alors passer en “terminée”
     * @param Mage_Sales_Model_Order $order
     */
    public function process($order)
    {
        $this->_resetProcessTrace();

        // Validating order before processing
        $this->_addTrace("Processing to ship order ...");
        $this->_addTrace("Validating order informations");
        $this->_validate($order);

        // Invoice order before shipment
        if ($order->canInvoice()) {
            if (!$order->hasInvoices()) {
                $this->_addTrace("Invoicing order before processing to shipment ...");
                Mage::getSingleton('sabre_dataflow/import_sales_orderStatus_processInvoice')
                    ->process($order, false);
                $this->_addTrace("Ok", 1);
            } else {
                Mage::throwException('Cannot invoice this order, it has invoice(s).');
            }
        }

        // Init Shipment
        $this->_addTrace("Init shipment");
        $shipment = $this->_initShipment($order);
        $this->_addTrace("Ok", 1);

        // Shipment register
        $this->_addTrace("Register shipment ...");
        $shipment->register();
        $this->_addTrace("Ok", 1);

        $shipment->setEmailSent(true);

        $shipment->getOrder()->setCustomerNoteNotify(true);
        $this->_addTrace("Order Set Customer Note Notify");

        $shipment->getOrder()->setIsInProcess(true);
        $this->_addTrace("Order Set Is In Process");

        // Save transaction
        $this->_addTrace("Resource transaction save ...");
        Mage::getModel('core/resource_transaction')
            ->addObject($shipment)
            ->addObject($shipment->getOrder())
            ->save();
        $this->_addTrace("Ok", 1);

        // Send mail
        $this->_addTrace("Send shipment mail");
        $shipment->sendEmail();
        $this->_addTrace("Ok", 1);

        // FORCE STATE
        if ($shipment->getOrder()->getState() != Mage_Sales_Model_Order::STATE_COMPLETE) {
            $this->_addTrace("Order state forcing change ...");
            $shipment->getOrder()->setState(Mage_Sales_Model_Order::STATE_COMPLETE);
            $this->_addTrace("state : {$shipment->getOrder()->getState()}", 1);
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
    }

    /**
     * Initialize shipment model instance
     *
     * @param Mage_Sales_Model_Order $order
     * @return Mage_Sales_Model_Order_Shipment
     */
    protected function _initShipment($order)
    {
        /**
         * Check shipment is available to create separate from invoice
         */
        if ($order->getForcedDoShipmentWithInvoice()) {
            Mage::throwException('Cannot do shipment for the order separately from invoice.');
        }
        /**
         * Check shipment create availability
         */
        if (!$order->canShip()) {
            Mage::throwException('Cannot do shipment for the order.');
        }

        //$savedQtys = $this->_getItemQtys();
        $shipment = Mage::getModel('sales/service_order', $order)->prepareShipment(array());


        if (!$shipment) {
            Mage::throwException('Cannot init shipment.');
        }

        return $shipment;
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
            $_qty = (int) $orderItem->getQtyToShip();
            $data[$orderItem->getId()] = "$_qty";
        }

        return array('items' => $data);
    }
}
