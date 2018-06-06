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
class Ayaline_Shop_Model_Mysql4_Shop_SpecialsSchedules_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('ayalineshop/shop_specialsSchedules');
    }

    public function addShopFilter($shopId)
    {
        $this->getSelect()->where('shop_id = ' . $shopId);

        return $this;
    }

    public function addOrderBySortDesc()
    {
        $this->getSelect()->order('sort desc');

        return $this;
    }

    public function addOrderBySort()
    {
        $this->getSelect()->order('sort');

        return $this;
    }
}
