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
class Ayaline_Shop_Model_Mysql4_Shop_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('ayalineshop/shop');
    }

    public function addOrderBy($orders)
    {
        $this->getSelect()->order($orders);

        return $this;
    }

    public function addIsActiveFilter()
    {
        $this->getSelect()->where('is_active = 1');

        return $this;
    }

    public function addPostcodeFilter($postcode)
    {
        $postcodeTable = $this->getTable('ayalineshop/postcodes');
        $this->getSelect()
             ->join($postcodeTable, 'main_table.shop_id=' . $postcodeTable . '.shop_id')
             ->where($postcodeTable . '.postcode = ?', $postcode);

        return $this;
    }

    public function addStoreFilter($storeId)
    {
        if ($storeId) {
            $storeRelationsTable = $this->getTable('ayalineshop/shop_store');
            $this->getSelect()
                 ->join($storeRelationsTable, 'main_table.shop_id=' . $storeRelationsTable . '.shop_id', array('main_shop_id' => 'shop_id'))
                 ->where($storeRelationsTable . '.store_id IN ' . '("' . $storeId . '","0")');
        }

        return $this;
    }
}
