<?php

/**
 * created: 2014
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 * Class Ayaline_GoogleUniversalAnalytics_Block_Tag
 *
 * @method array getOrderIds()
 * @method Ayaline_GoogleUniversalAnalytics_Block_Tag setOrderIds(array $orderIds)
 */
class Ayaline_GoogleUniversalAnalytics_Block_Tag extends Mage_Core_Block_Template
{
    const URL = '//www.google-analytics.com/analytics.js';
    const DEBUG_URL = '//www.google-analytics.com/analytics_debug.js';

    protected $_isEnabled = false;

    protected $_createConfig = array();

    protected $_config;

    protected $_onepageStepsFlag = false;

    protected function _getConfig()
    {
        return $this->_config;
    }

    /**
     * @return boolean
     */
    public function isOnepageStepsFlag()
    {
        return $this->_onepageStepsFlag;
    }

    /**
     * @param boolean $onepageStepsFlag
     */
    public function setOnepageStepsFlag($onepageStepsFlag)
    {
        $this->_onepageStepsFlag = $onepageStepsFlag;
    }


    protected function _construct()
    {
        parent::_construct();

        $this->_config = Mage::getSingleton('ayaline_gua/system_config');
        $this->_isEnabled = ($this->_config->isEnabled() && $this->_config->getWebPropertyId());

    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $userAllowCookie = (!Mage::helper('core/cookie')->isUserNotAllowSaveCookie());

        return ($this->_isEnabled && $userAllowCookie)
            ? parent::_toHtml()
            : '';
    }

    public function getAnalyticsUrl()
    {
        return $this->_config->debug() ? self::DEBUG_URL : self::URL;
    }

    public function canUseDebugTrace()
    {
        return $this->_config->debug() && $this->_config->debugTrace();
    }

    // tags

    /**
     * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/advanced#creation
     * @return string
     */
    public function getCreate()
    {
        $webPropertyId = $this->_config->getWebPropertyId();

        if ($createConfig = $this->_config->getCreateTrackerConfig()) {
            $createConfig = Mage::helper('core')->jsonEncode($createConfig);

            return "ga('create', '{$webPropertyId}', 'auto', {$createConfig});";
        }

        return "ga('create', '{$webPropertyId}', 'auto');";
    }

    /**
     * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/advanced#anonymizeip
     * @return string
     */
    public function getAnonymizeIp()
    {
        $anonymizeIp = $this->_config->anonymizeIp() ? 'true' : 'false';

        return "ga('set', 'anonymizeIp', {$anonymizeIp});";
    }

    /**
     * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/advanced#ssl
     * @return string
     */
    public function getForceSsl()
    {
        $forceSsl = $this->_config->forceSsl() ? 'true' : 'false';

        return "ga('set', 'forceSSL', {$forceSsl});";
    }

    /**
     * @see https://support.google.com/analytics/answer/3123666
     * @return string
     */
    public function getUserId()
    {
        if ($this->_config->canUseUserId() && Mage::getSingleton('customer/session')->isLoggedIn()) {
//            $customer = Mage::helper('customer')->getCustomer();
//            $userId = $customer->getId() . $customer->getCreatedAtTimestamp();

            $userId = md5(Mage::getSingleton('customer/session')->getSessionId());

            return "ga('set', 'userId', '{$userId}');";
        }

        return '';
    }

    // ecommerce

    public function getEcommerce()
    {
        $orderIds = $this->getOrderIds();
        if (!empty($orderIds)) {
            $orders = Mage::getResourceModel('sales/order_collection')
                ->addFieldToFilter('entity_id', array('in' => $orderIds));

            if ($orders->getSize()) {
                $ecommerce = '';
                $_useCategoryOnOrderItem = $this->_config->useCategoryOnOrderItem();
                /** @var $_order Mage_Sales_Model_Order */
                foreach ($orders as $_order) {
                    $_transactionId = $_order->getIncrementId();

                    $_transaction = Mage::helper('core')->jsonEncode(
                        array(
                            'id'          => $_transactionId,
                            'affiliation' => $this->jsQuoteEscape($_order->getStore()->getFrontendName()),
                            //'revenue' => $_order->getGrandTotal(),
                            //'shipping' => $_order->getShippingAmount(),
                            //'tax' => $_order->getTaxAmount(),
                            'revenue'     => $_order->getBaseGrandTotal(),
                            'shipping'    => $_order->getBaseShippingAmount(),
                            'tax'         => $_order->getBaseTaxAmount(),
                            'currency'    => $_order->getStoreCurrencyCode(),
                        ));

                    $ecommerce .= "ga('ecommerce:addTransaction', {$_transaction});";

                    /** @var $_orderItem Mage_Sales_Model_Order_Item */
                    foreach ($_order->getAllVisibleItems() as $_orderItem) {

                        $_item = Mage::helper('core')->jsonEncode(
                            array(
                                'id'       => $_transactionId,
                                'name'     => $this->jsQuoteEscape($_orderItem->getName()),
                                'sku'      => $this->jsQuoteEscape($_orderItem->getSku()),
                                'category' => $_useCategoryOnOrderItem ? $this->_getOrderItemCategory($_orderItem) : null,
                                //'price' => $_orderItem->getPrice(),
                                'price'    => $_orderItem->getBasePrice(),
                                'quantity' => $_orderItem->getQtyOrdered(),
                            ));

                        $ecommerce .= "ga('ecommerce:addItem', {$_item});";
                    }
                }

                if ($ecommerce != '') {
                    return "ga('require', 'ecommerce');{$ecommerce}ga('ecommerce:send');";
                }
            }
        }

        return null;
    }

    /**
     * @param Mage_Sales_Model_Order_Item $orderItem
     * @return string
     */
    protected function _getOrderItemCategory($orderItem)
    {
        $categories = Mage::getResourceModel('catalog/category_collection')
            ->joinField(
                'product_id',
                'catalog/category_product',
                'product_id',
                'category_id = entity_id',
                null
            )
            ->addFieldToFilter('product_id', (int)$orderItem->getProductId())
            ->addAttributeToSelect('name')
            ->addAttributeToSort('level', Varien_Data_Collection::SORT_ORDER_DESC)
            ->setPageSize(1);

        if ($categories->getSize()) {
            return $this->jsQuoteEscape($categories->getFirstItem()->getData('name'));
        }

        return null;
    }

    /**
     * tracking onepage steps
     * @return string
     */
    public function getOnepageTracking()
    {
        if ($this->_getConfig()->onepageTrackingIsActive() && $this->_onepageStepsFlag) {
            $result = "onepageSteps = {$this->_getConfig()->getOnepageTrackingSteps()};
if (window.location.href.include('?register')) {
    ga('send', 'event', {
        hitType: 'event',
        eventCategory: 'onepage steps',
        eventAction: 'click',
        eventLabel: onepageSteps['register']
    });
}
if (Ajax.Responders) {
    Ajax.Responders.register({
        onComplete: function (response) {
            if (!response.url.include('progress') && !response.url.include('updateOpTotal')) {
                if (response.url.include('saveOrder')) {
                    ga('send', 'event', {
                        hitType: 'event',
                        eventCategory: 'onepage steps',
                        eventAction: 'click',
                        eventLabel: onepageSteps['save_order']
                    });
                } else if (accordion.currentSection) {
                    if (accordion.currentSection == 'opc-shipping_method'
                        || (accordion.currentSection == 'opc-shipping'
                    && response.url.include('getAdditional/') )
                        || !( response.url.include('getAdditional/')
                        || response.url.include('saveMethod/')
                        || response.url.include('saveShipping/') )) {
                            ga('send', 'event', {
                                hitType: 'event',
                                eventCategory: 'onepage steps',
                                eventAction: 'click',
                                eventLabel: onepageSteps[accordion.currentSection.replace('opc-', '')]
                            });
                    }
                }
            }
        }
    });
}";
            return $result;
        }
        return '';
    }

}