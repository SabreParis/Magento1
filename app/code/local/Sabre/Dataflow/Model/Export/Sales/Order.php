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
class Sabre_Dataflow_Model_Export_Sales_Order extends Ayaline_DataflowManager_Model_Export_Abstract
{

    const XML_PATH_CONFIG_FILTER_STATES = 'ayaline_dataflowmanager/export_sales_order/filter_states';
    const ORDER_ERP_STATUS_PENDING = 'pending';
    const ORDER_ERP_STATUS_SUCCESS = 'success';
    const ORDER_ERP_STATUS_ERROR = 'error';
    const ORDER_ERP_STATUS_FORCE = 'force';
    const DATE_FORMAT = 'y-MM-dd';

//  protected $_sendEmail = false;

    protected $_orderIds = array();
    protected $_websiteMappingErp = array();

    /**
     * {@inheritdoc}
     */
    protected function _getDocumentation()
    {
        return <<<DOC
Export des commandes
--orders    Give order ids to export, separated by ","
DOC;
    }

    /**
     * {@inheritdoc}
     */
    protected function _validate()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function _getFilename()
    {
        return 'commandes-web_' . date('YmdHis') . '.csv';
    }

    protected function _export()
    {
        $this->_startProfiling(__FUNCTION__);
        $this->_log(__METHOD__);

        // get orders
        /* @var $orders Mage_Sales_Model_Resource_Order_Collection */
        $orders = null;
        if (($orderIdsParam = $this->_getArgument('orders', false))) {
            /**
             * Filtering orders using args --orders 
             */
            $orderIdsParam = is_array($orderIdsParam) ? $orderIdsParam : explode(',', $orderIdsParam);
            $orderIdsParam = array_map('trim', $orderIdsParam);

            $orders = $this->_getOrders2ExportByIds($orderIdsParam);
            $this->_log(' Filtering orders on entity_id ( ' . implode(', ', $orderIdsParam) . ' )');
        } elseif (($orederStatesParam = $this->_getArgument('order_states', false))) {

            /**
             * Filtering orders using args --order_states
             */
            $orederStatesParam = is_array($orederStatesParam) ? $orederStatesParam : explode(',', $orederStatesParam);
            $orederStatesParam = array_map('trim', $orederStatesParam);

            $orders = $this->_getOrders2ExportByStates($orederStatesParam);
            $this->_log(' Filtering orders on states ( ' . implode(', ', $orederStatesParam) . ' )');
        } else {

            /**
             * Filtering orders using config
             */
            $configFilterStatus = Mage::getStoreConfig(self::XML_PATH_CONFIG_FILTER_STATES);
            $filterStates = is_array($configFilterStatus) ? $configFilterStatus : explode(',', $configFilterStatus);
            $filterStates = array_map('trim', $filterStates);

            $orders = $this->_getOrders2Export();
            $this->_log(' Filtering orders on states ( ' . implode(', ', $filterStates) . ' )');
        }

        // Iterate on collection items
        if ($orders) {
            Mage::getSingleton('core/resource_iterator')->walk(
                $orders->getSelect(),
                array(array($this, 'processOrder'))
            );
        }

        if (!empty($this->_orderIds)) {
            try {
                $this->_log(' Update order grid: ' . Mage::helper('core')->jsonEncode($this->_orderIds));
                Mage::getResourceSingleton('sales/order')->updateGridRecords($this->_orderIds);
                $this->_log('  Order grid updated');
            } catch (Exception $e) {
                $this->_log("  An error occurred while updating order grid: {$e->getMessage()}");
            }
        } else {
            $this->_deleteFileIfEmpty = true;
        }

        $this->_log('');

        $this->_stopProfiling(__FUNCTION__);
    }

    /**
     * 
     * @param array|string $orderStates
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    protected function _getOrders2ExportByIds($orderIds)
    {
        if (is_string($orderIds)) {
            $orderIds = array_map('trim', explode(',', $orderIds));
        }
        
        return Mage::getResourceModel('sales/order_collection')
                ->addAttributeToFilter('entity_id', array('in' => $orderIds));
    }

    /**
     * 
     * @param array|string $orderStates
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    protected function _getOrders2ExportByStates($orderStates)
    {
        if (is_string($orderStates)) {
            $orderStates = array_map('trim', explode(',', $orderStates));
        }
        
        return Mage::getResourceModel('sales/order_collection')
                ->addAttributeToFilter('states', array('in' => $orderStates));
    }

    /**
     * 
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    protected function _getOrders2Export()
    {
        /**
         * Filtering orders using config
         */
        $configFilterStatus = Mage::getStoreConfig(self::XML_PATH_CONFIG_FILTER_STATES);
        $filterStates = is_array($configFilterStatus) ? $configFilterStatus : explode(',', $configFilterStatus);
        $filterStates = array_map('trim', $filterStates);

        /* @var $orders Mage_Sales_Model_Resource_Order_Collection */
        $orders = Mage::getResourceModel('sales/order_collection');
        $adapter = $orders->getSelect()->getAdapter();

        if ($filterStates && !empty($filterStates)) {
            // Filterring on config states & "pending" erp_status + "forced" erp_status
            $orders
                ->getSelect()
                ->where(
                    implode(' OR ', array(
                        sprintf("(%s)", implode(' AND  ', array(
                            $adapter->quoteInto("main_table.state IN (?)", $filterStates),
                            $adapter->quoteInto("main_table.erp_status=?", self::ORDER_ERP_STATUS_PENDING),
                        ))),
                        sprintf("(%s)", $adapter->quoteInto("main_table.erp_status=?", self::ORDER_ERP_STATUS_FORCE)),
            )));
        } else {
            // Filterring on + "forced" or "pending" erp_status
            $orders
                ->getSelect()
                ->where(
                    implode(' OR ', array(
                        sprintf(
                            "(%s)", $adapter->quoteInto("main_table.erp_status=?", self::ORDER_ERP_STATUS_PENDING)),
                        sprintf(
                            "(%s)", $adapter->quoteInto("main_table.erp_status=?", self::ORDER_ERP_STATUS_FORCE)),
            )));
        }

        return $orders;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return string
     */
    protected function _getShippingMode($order)
    {
        return (string) $order->getShippingMethod();
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return string
     */
    protected function _getPaymentMode($order)
    {
        return (string) $order->getPayment()->getMethod();
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return Mage_Sales_Model_Order_Payment_Transaction
     */
    protected function _getPaymentTransaction($order)
    {
        /* @var $paymentTr Mage_Sales_Model_Resource_Order_Payment_Transaction_Collection */
        $paymentTr = Mage::getResourceModel('sales/order_payment_transaction_collection');
        $paymentTr
            ->addFieldToFilter('order_id', array('eq' => $order->getId()))
            ->setPageSize(1);

        return $paymentTr->getFirstItem();
    }

    /**
     * @todo Delete if not used any where
     * @param Mage_Sales_Model_Order $order
     * @return string
     */
    protected function _getCustomerOrdersCount($order)
    {
        return Mage::getResourceHelper('sabre_dataflow')->getCustomerOrdersCount($order->getCustomerId());
    }

    /**
     * @param Mage_Sales_Model_Order_Address $shippingAddress
     * @return string
     */
    protected function _getShippingAddressStreet($shippingAddress)
    {
        return implode(' ', $shippingAddress->getStreet());
    }

    /**
     * @param Mage_Sales_Model_Order_Address $shippingAddress
     * @return string
     */
    protected function _getBillingAddressStreet($shippingAddress)
    {
        return implode(' ', $shippingAddress->getStreet());
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return string
     */
    protected function _getOrderFilename($order)
    {
        return "order_{$order->getIncrementId()}.csv";
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return string
     */
    protected function _getGiftMessageText($order)
    {
        if ($order->getGiftMessageId()) {
            /* @var $giftMsg Mage_GiftMessage_Model_Resource_Message_Collection */
            $giftMsgCollection = Mage::getResourceModel('giftmessage/message_collection');
            $giftMsgCollection
                ->addFieldToFilter(
                    'gift_message_id', array('eq' => $order->getGiftMessageId()))
                ->setPageSize(1);

            /* @var $giftMsgModel Mage_GiftMessage_Model_Message */
            $giftMsgModel = $giftMsgCollection->getFirstItem();
            if ($giftMsgModel && $giftMsgModel->getId()) {

                return str_replace("\n", " ", $giftMsgModel->getMessage());
            }
        }

        return '';
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return array
     */
    protected function _getOrderRow($order)
    {
        /* @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());

        // billing
        /* @var $billingAddress Mage_Sales_Model_Order_Address */
        $billingAddress = $order->getBillingAddress();
        if (!$billingAddress) {
            Mage::throwException("No billing address");
        }

        // shipping
        /* @var $shippingAddress Mage_Sales_Model_Order_Address */
        $shippingAddress = $order->getShippingAddress();
        if (!$shippingAddress) {
            Mage::throwException("No shipping address");
        }

        // order_id - reference - created_at - status - method - total_ttc - transaction_number - packing
        // customer_civility - customer_name - customer_email - customer_phone - customer_login - customer_orders_count
        // shipping_customer_name - shipping_company - shipping_address_1 - shipping_address_2 - shipping_zipcode
        // shipping_city - shipping_phone - shipping_country_name - shipping_country_iso
        // billing_customer_name - billing_company - billing_address 1 - billing_address 2 - billing_zipcode
        // billing_city - billing_phone - billing_country_name - billing_country_iso
        // magento_id - shipping_code - gift_message

        $row = array(
            'increment_id' => $order->getIncrementId(),
            'reference' => $order->getIncrementId(),
            'created_at' => (new Zend_Date($order->getData('created_at')))->toString(self::DATE_FORMAT),
            'status' => $order->getData('status'),
            'method' => $this->_getPaymentMode($order),
            'total_ttc' => $this->_formatPrice($order, $order->getGrandTotal()),
            'transaction_number' => $this->_getPaymentTransaction($order)->getTxnId(),
            'packing' => false,
            // Customer informations
            'customer_civility' => $customer->getPrefix(),
            'customer_name' => $customer->getName(),
            'customer_prefix' => $customer->getPrefix(),
            'customer_firstname' => $customer->getFirstname(),
            'customer_lastname' => $customer->getLastname(),
            'customer_email' => $customer->getEmail(),
            'customer_phone' => $billingAddress->getTelephone(), /** @todo Using $billingAddress Or $shippingAddress */
            'customer_login' => $customer->getEmail(),
            'customer_orders_count' => '', // Always empty
            // Shipping address informations
            'shipping_customer_name' => $shippingAddress->getName(),
            'shipping_customer_prefix' => $shippingAddress->getPrefix(),
            'shipping_customer_firstname' => $shippingAddress->getFirstname(),
            'shipping_customer_lastname' => $shippingAddress->getLastname(),
            'shipping_company' => $shippingAddress->getCompany(),
            'shipping_address_1' => $shippingAddress->getStreet1(),
            'shipping_address_2' => $shippingAddress->getStreet2(),
            'shipping_zipcode' => $shippingAddress->getPostcode(),
            'shipping_city' => $shippingAddress->getCity(),
            'shipping_phone' => $shippingAddress->getTelephone(),
            'shipping_country_name' => $shippingAddress->getCountryModel()->getName(),
            'shipping_country_iso' => $shippingAddress->getCountryModel()->getIso2Code(),
            // Billing address informations
            'billing_customer_name' => $billingAddress->getName(),
            'billing_customer_prefix' => $billingAddress->getPrefix(),
            'billing_customer_firstname' => $billingAddress->getFirstname(),
            'billing_customer_lastname' => $billingAddress->getLastname(),
            'billing_company' => $billingAddress->getCompany(),
            'billing_address_1' => $billingAddress->getStreet1(),
            'billing_address_2' => $billingAddress->getStreet2(),
            'billing_zipcode' => $billingAddress->getPostcode(),
            'billing_city' => $billingAddress->getCity(),
            'billing_phone' => $billingAddress->getTelephone(),
            'billing_country_name' => $billingAddress->getCountryModel()->getName(),
            'billing_country_iso' => $billingAddress->getCountryModel()->getIso2Code(),
            // Other informations
            'magento_id' => $customer->getId(), // Id client magento
            'shipping_code' => $order->getData('shipping_description'),
            'gift_message' => $order->getGiftMessageId() ? $this->_getGiftMessageText($order) : '',
            'website_code' => isset($this->_websiteMappingErp[$order->getStore()->getWebsite()->getCode()]) ? $this->_websiteMappingErp[$order->getStore()->getWebsite()->getCode()] : ""
        );

        return $row;
    }

    /**
     * @param Mage_Sales_Model_Order_Item $orderItem
     * @return array
     */
    protected function _getOrderItemRow($orderItem)
    {
        //order_id,sku,color,quantity,unit_price,total_ttc
        $row = array();
        $row['order_id'] = $orderItem->getOrder()->getIncrementId();
        $row['sku'] = $orderItem->getSku();
        $row['quantity'] = (int) $orderItem->getQtyOrdered();
        $row['unit_price'] = $this->_formatPrice($orderItem->getOrder(), $orderItem->getPriceInclTax());
        $row['total_ttc'] = $this->_formatPrice($orderItem->getOrder(), $orderItem->getRowTotalInclTax());

        return $row;
    }

    /**
     * @return array
     */
    protected function _getOrderRowHeader()
    {
        return array(
            "order_id",
            "reference",
            "created_at",
            "status",
            "method",
            "total_ttc",
            "transaction_number",
            "packing",
            "customer_civility",
            "customer_name",
            "customer_prefix",
            "customer_firstname",
            "customer_lastname",
            "customer_email",
            "customer_phone",
            "customer_login",
            "customer_orders_count",
            "shipping_customer_name",
            "shipping_customer_prefix",
            "shipping_customer_firstname",
            "shipping_customer_lastname",
            "shipping_company",
            "shipping_address_1",
            "shipping_address_2",
            "shipping_zipcode",
            "shipping_city",
            "shipping_phone",
            "shipping_country_name",
            "shipping_country_iso",
            "billing_customer_name",
            "billing_customer_prefix",
            "billing_customer_firstname",
            "billing_customer_lastname",
            "billing_company",
            "billing_address_1",
            "billing_address_2",
            "billing_zipcode",
            "billing_city",
            "billing_phone",
            "billing_country_name",
            "billing_country_iso",
            "magento_id",
            "shipping_code",
            "gift_message",
            "website_code"
        );
    }

    /**
     * @return array
     */
    protected function _getOrderItemRowHeader()
    {
        return array(
            "order_id",
            "sku",
            "quantity",
            "unit_price",
            "total_ttc",
        );
    }

    public function processOrder($args)
    {
        $separator = str_repeat('-', strlen(__METHOD__));
        $this->_log($separator);
        $this->_log(__METHOD__);


        /** @var Mage_Sales_Model_Order $order */
        $order = Mage::getModel('sales/order')->setData($args['row']);

        //$order = $args['order']
        $this->_orderIds[] = $order->getId();
        $this->_log("Process Order {$order->getIncrementId()} ({$order->getId()})");

        $orderFilename = $this->_getOrderFilename($order);
        $this->_io->streamOpen($orderFilename);
        try {

            $rowOrder = $this->_getOrderRow($order);

            $this->_log('    Data order:' . Mage::helper('core')->jsonEncode($rowOrder));

            // Header row type order
            $this->_io->streamWriteCsv($this->_getOrderRowHeader(), ',', '"');

            // Data row type order
            $this->_io->streamWriteCsv($rowOrder, ',', '"');

            // Header row type item
            $this->_io->streamWriteCsv($this->_getOrderItemRowHeader(), ',', '"');

            /** @var Mage_Sales_Model_Order_Item $_item */
            foreach ($order->getAllVisibleItems() as $_item) {
                $this->_log("         Item: {$_item->getSku()}");
                $rowItem = $this->_getOrderItemRow($_item);

                $this->_log('         Data order item:' . Mage::helper('core')->jsonEncode($rowItem));

                // Data row type item
                $this->_io->streamWriteCsv($rowItem, ',', '"');
            }


            // Order flagged as 'exported'
            $this->_log('    Order Erp Status : SUCCESS');

            $order->setData('erp_status', self::ORDER_ERP_STATUS_SUCCESS);

            // Transfer processed files before finishing them
            $this->_transferFiles();

            $this->_finishFile($orderFilename);
        } catch (Mage_Core_Exception $e) {

            $this->_log($e->getMessage(), Zend_Log::ERR);

            // Order Erp Status : Error
            $this->_log('    Order Erp Status : ERROR', Zend_Log::ERR);
            $order->setData('erp_status', self::ORDER_ERP_STATUS_ERROR);
            $order->setData('erp_error_msg', $e->getMessage());

            // Delete file from processing folder
            $this->_deleteFile($orderFilename);
        } catch (Exception $e) {

            $this->_log($e->getMessage(), Zend_Log::ERR);
            $this->_logException($e);

            // Order Erp Status : Error
            $this->_log('    Order Erp Status : ERROR', Zend_Log::ERR);
            $order->setData('erp_status', self::ORDER_ERP_STATUS_ERROR);
            $order->setData('erp_error_msg', $e->getMessage());

            // Delete file from processing folder
            $this->_deleteFile($orderFilename);
        } finally {

            $order->setData('erp_export_date', now());

            $order->getResource()->saveAttribute($order, 'erp_status');
            $order->getResource()->saveAttribute($order, 'erp_export_date');
            $order->getResource()->saveAttribute($order, 'erp_error_msg');
        }

        $this->_log($separator . "\n");
    }

    /**
     * 
     * @param Mage_Sales_Model_Order $order
     * @param float $price
     * @param int $precision
     * @return string
     */
    protected function _formatPrice($order, $price, $precision = 2)
    {
        $formatedPrice = $order->getOrderCurrency()->formatPrecision($price, $precision, array('display' => Zend_Currency::NO_SYMBOL), false, false);

        return str_replace(',', '.', trim($formatedPrice));
    }

    protected function _deleteFile($filename)
    {
        return $this->_finishFile($filename, true);
    }

    protected function _finishFile($filename, $forceDelete = false)
    {
        if (!$filename) {
            return $this;
        }

        return parent::_finishFile($filename, $forceDelete);
    }

    protected function _beforeExport()
    {

        $websitesMapping = Mage::getStoreConfig("ayaline_dataflowmanager/import_product/config_mapping_website");
        $websitesMapping = unserialize($websitesMapping);
        $varWebsiteMapping = array();
        foreach ($websitesMapping as $_item) {
            $varWebsiteMapping[$_item['magento_website_code']] = $_item['sabre_website_code'];
        }
        $this->_websiteMappingErp = $varWebsiteMapping;

        var_dump($varWebsiteMapping);

        return $this;
    }
}
