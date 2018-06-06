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
class Ayaline_Shop_Block_View extends Mage_Core_Block_Template
{
    protected $_shop = null;

    function getShop()
    {
        if ($this->_shop == null) {
            $id = $this->getRequest()->getParam('id');
            $this->_shop = Mage::getModel('ayalineshop/shop');
            if ($id) {
                $this->_shop = $this->_shop->load($id);
            }

        }

        return $this->_shop;
    }
}
