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
class Ayaline_Shop_Block_List extends Mage_Core_Block_Template
{
    protected $_shops = null;
    protected $_middleSize = 0;
    protected $_partOne = null;
    protected $_partTwo = null;
    protected $_postcodeFilterValue = '';
    protected $_countryFilterValue = '';

    protected function _construct()
    {
        parent::_construct();
        $params = Mage::app()->getRequest()->getParams();
        if (array_key_exists('postcode', $params)) {
            $this->_postcodeFilterValue = $params['postcode'];
        }
        if (array_key_exists('country', $params)) {
            $this->_countryFilterValue = $params['country'];
        }
    }

    protected function _getStore()
    {
        return Mage::app()->getStore();
    }

    public function getPostcodeFilterValue()
    {
        return $this->_postcodeFilterValue;
    }

    public function getCountryFilterValue()
    {
        return $this->_countryFilterValue;
    }

    public function getShops()
    {
        if (!$this->_shops) {
            $this->_shops = Mage::getModel('ayalineshop/shop')->getCollection();
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

        return $this->_shops;
    }

    public function getViewUrl($id)
    {
        return $this->getUrl('*/*/view', array('id' => $id));
    }

    public function getShopsPartOne()
    {
        if ($this->_partOne == null) {
            $this->breakShops();
        }

        return $this->_partOne;
    }

    public function getShopsPartTwo()
    {
        if ($this->_partTwo == null) {
            $this->breakShops();
        }

        return $this->_partTwo;
    }

    private function breakShops()
    {
        $size = $this->getShops()->getSize();
        $this->_middleSize = round($this->getShops()->getSize() / 2);
        $nb = 0;
        $part = 1;
        $this->_partOne = array();
        $this->_partTwo = array();
        foreach ($this->getShops() as $shop) {
            if ($part == 1) {
                $this->_partOne[] = $shop;
            } else {
                $this->_partTwo[] = $shop;
            }
            $nb++;

            if ($nb > $this->_middleSize) {
                $nb = 0;
                $part++;
            }
        }

    }

    public function getMiddleSize()
    {
        $this->breakShops();

        return $this->_middleSize;
    }
}
