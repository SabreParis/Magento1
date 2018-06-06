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
class Ayaline_Shop_Model_Mysql4_Shop_Group_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('ayalineshop/shop_group');
    }

    public function getArray()
    {
        $tab = array();
        foreach ($this as $group) {
            $tab[$group->getId()] = $group->getName();
        }

        return $tab;
    }

    public function addOrderByName()
    {
        $this->getSelect()->order('name');

        return $this;
    }
}
