<?php

/**
 * created : 2 juil. 2012
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_Billboard_Block_Billboard_Product extends Ayaline_Billboard_Block_Billboard
{

    protected function _getProductId()
    {
        if (Mage::registry('current_product')) {
            return Mage::registry('current_product')->getId();
        }

        return 0;
    }

    /**
     * Add some keys for cache
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $keyInfo = parent::getCacheKeyInfo();
        $keyInfo[] = $this->_getProductId();

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
                ->addProductFilter($this->_getProductId())//	associate to current product,
                ->addCustomerGroupFilter($this->_getCustomerGroup());    //	associate to this customer
            ;

            if ($this->canFilterByDatetime()) {
                $collection->addDateTimeFilter();                        //	and visible now
            }

            $this->_billboards = $collection;
        }

        return $this->_billboards;
    }

}