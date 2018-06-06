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
class Ayaline_GoogleUniversalAnalytics_Model_Observer
{

    /**
     * Set order ids to GUA block
     *
     * Event: checkout_onepage_controller_success_action (frontend)
     * Event: checkout_multishipping_controller_success_action (frontend)
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function setOrderIds($observer)
    {
        $orderIds = $observer->getEvent()->getData('order_ids');

        if (empty($orderIds)) {
            return $this;
        }

        /** @var $block Ayaline_GoogleUniversalAnalytics_Block_Tag */
        $block = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('ayaline.google.universal.analytics.tag');
        if ($block) {
            $block->setOrderIds($orderIds);
        }

        return $this;
    }

} 