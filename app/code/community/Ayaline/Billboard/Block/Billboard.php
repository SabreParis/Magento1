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
class Ayaline_Billboard_Block_Billboard extends Mage_Core_Block_Template
{

    protected $_billboards = null;

    /**
     * Configuration model
     *
     * @var Ayaline_Billboard_Model_System_Config
     */
    protected $_config;

    protected function _construct()
    {
        parent::_construct();
        $this->_config = Mage::getSingleton('ayalinebillboard/system_config');
        $this->_config->setStore($this->_getStore());
    }

    /**
     * Overload function to return configuration cache lifetime
     *
     * @return int
     */
    public function getCacheLifetime()
    {
        if (!$this->hasData('cache_lifetime')) {
            return $this->getConfig()->getDefaultCacheLifetime();
        }

        return $this->getData('cache_lifetime');
    }

    /**
     * Check if we can filter by date time, if not set we get the configuration
     *
     * @return bool
     */
    public function canFilterByDatetime()
    {
        if (!$this->hasData('filter_by_datetime')) {
            return $this->getConfig()->filterByDatetime();
        }

        return (bool)$this->getData('filter_by_datetime');
    }

    /**
     * Add some keys for cache
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $keyInfo = parent::getCacheKeyInfo();
        $keyInfo[] = 'ayalinebillboard';
        $keyInfo[] = $this->getNameInLayout();
        $keyInfo[] = $this->getRequest()->isSecure();
        $keyInfo[] = Mage::getDesign()->getPackageName();
        $keyInfo[] = Mage::getDesign()->getTheme('template');
        $keyInfo[] = $this->_getCategoryId();
        $keyInfo[] = $this->_getCustomerGroup();
        $keyInfo[] = get_class($this);

        return $keyInfo;
    }

    /**
     * Retrieve billboards collection
     *
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function getBillboards()
    {
        if (is_null($this->_billboards)) {
            /* @var $collection Ayaline_Billboard_Model_Mysql4_Billboard_Collection */
            $collection = Mage::getResourceModel('ayalinebillboard/billboard_collection');
            $collection
                ->addStatusFilter()//	only active billboards,
                ->addStoreFilter($this->_getStore())//	associate to this store,
                ->addCategoryFilter($this->_getCategoryId())//	associate to current category,
                ->addCustomerGroupFilter($this->_getCustomerGroup());    //	associate to this customer
            ;

            if ($this->canFilterByDatetime()) {
                $collection->addDateTimeFilter();                        //	and visible now
            }

            $this->_billboards = $collection;
        }

        return $this->_billboards;
    }

    /**
     * Retrieve current store
     *
     * @return Mage_Core_Model_Store
     */
    protected function _getStore()
    {
        return Mage::app()->getStore();
    }

    /**
     * Retrieve current category id
     *
     * @return int
     */
    protected function _getCategoryId()
    {
        if (Mage::registry('current_category')) {
            return Mage::registry('current_category')->getId();
        } else {
            return $this->_getStore()->getRootCategoryId();
        }
    }

    /**
     * Retrieve current customer group
     *
     * @return int
     */
    protected function _getCustomerGroup()
    {
        return Mage::getSingleton('customer/session')->getCustomerGroupId();
    }

    /**
     * @param Ayaline_Billboard_Model_Billboard $billboard
     * @return string
     */
    public function getTitle($billboard)
    {
        return $this->stripTags($billboard->getTitle());
    }

    /**
     * Return config model
     *
     * @return Ayaline_Billboard_Model_System_Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Create an unique id for HTML element
     *
     * @return string
     */
    public function getUniqueId()
    {
        return Mage::helper('core')->uniqHash('billboards_container_');
    }

}