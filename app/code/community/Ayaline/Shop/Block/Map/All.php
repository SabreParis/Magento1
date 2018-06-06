<?php

/**
 * created : 10/03/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Shop
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_Shop_Block_Map_All extends Ayaline_Shop_Block_List
{
    public function getShops()
    {
        if (!$this->_shops) {
            $this->_shops = Mage::getModel('ayalineshop/shop')->getCollection();
            $params = Mage::app()->getRequest()->getParams();
            if ($this->_postcodeFilterValue) {
                $this->_shops->addPostcodeFilter($this->_postcodeFilterValue);
            }
            if ($this->_countryFilterValue) {
                $this->_shops->addFieldToFilter('country_id', $this->_countryFilterValue);
            }
            $this->_shops->addIsActiveFilter()
                         ->addStoreFilter($this->_getStore()->getId())
                         ->addOrderBy(
                             array(
                                 'main_table.postcode',
                                 'city'
                             )
                         );
        }
        $this->_shops->load();

        return $this->_shops;
    }
}
