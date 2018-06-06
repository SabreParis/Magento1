<?php
/**
 * created : 10/08/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 *
 * @package Ayaline_Billboard
 */
class Ayaline_Billboard_Model_System_Config
{

    const XML_PATH_AYALINE_BILLBOARD_BACKEND_MULTI_TYPE = 'ayalinebillboard/backend/multi_type';

    const XML_PATH_AYALINE_BILLBOARD_FRONTEND_FILTER_DATETIME = 'ayalinebillboard/frontend/filter_datetime';
    const XML_PATH_AYALINE_BILLBOARD_FRONTEND_DEFAULT_CACHE_LIFTIME = 'ayalinebillboard/frontend/default_cache_lifetime';

    const XML_PATH_AYALINE_BILLBOARD_PROTOSHOW_INTERVAL = 'ayalinebillboard/protoshow/interval';
    const XML_PATH_AYALINE_BILLBOARD_PROTOSHOW_AUTOPLAY = 'ayalinebillboard/protoshow/autoplay';
    const XML_PATH_AYALINE_BILLBOARD_PROTOSHOW_TRANSITION_TIME = 'ayalinebillboard/protoshow/transition_time';
    const XML_PATH_AYALINE_BILLBOARD_PROTOSHOW_MAN_TRANSITION_TIME = 'ayalinebillboard/protoshow/man_transition_time';

    const XML_NODE_BILLBOARDS_BILLBOARD_RELATED_CACHE_TYPES = 'global/billboards/billboard/related_cache_types';

    protected $_store = null;

    public function setStore($store)
    {
        $this->_store = $store;

        return $this;
    }

    public function isActiveMultiType()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_BILLBOARD_BACKEND_MULTI_TYPE, Mage_Core_Model_App::ADMIN_STORE_ID);
    }

    public function filterByDatetime()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_BILLBOARD_FRONTEND_FILTER_DATETIME, $this->_store);
    }

    public function getDefaultCacheLifetime()
    {
        return Mage::getStoreConfig(self::XML_PATH_AYALINE_BILLBOARD_FRONTEND_DEFAULT_CACHE_LIFTIME, $this->_store);
    }

    public function getCacheTypes()
    {
        $types = Mage::getConfig()->getNode(self::XML_NODE_BILLBOARDS_BILLBOARD_RELATED_CACHE_TYPES);
        if ($types) {
            return $types->asArray();
        }

        return false;
    }

    //	Protoshow Config
    public function getProtoshowInterval()
    {
        return Mage::getStoreConfig(self::XML_PATH_AYALINE_BILLBOARD_PROTOSHOW_INTERVAL, $this->_store) * 1000;    //	convert to ms
    }

    public function getProtoshowAutoplay()
    {
        return Mage::getStoreConfig(self::XML_PATH_AYALINE_BILLBOARD_PROTOSHOW_AUTOPLAY, $this->_store);
    }

    public function getProtoshowTransitionTime()
    {
        return Mage::getStoreConfig(self::XML_PATH_AYALINE_BILLBOARD_PROTOSHOW_TRANSITION_TIME, $this->_store);
    }

    public function getProtoshowManTransitionTime()
    {
        return Mage::getStoreConfig(self::XML_PATH_AYALINE_BILLBOARD_PROTOSHOW_MAN_TRANSITION_TIME, $this->_store);
    }
}