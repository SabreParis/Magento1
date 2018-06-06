<?php

/**
 * created : 08/10/2015
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Shop_Helper_Data extends Mage_Core_Helper_Abstract
{

    const USED_FOR_SHIPPING_TRUE = 1;

    protected $_options = array();
    protected $_shop;

    public function getAllshippingAgencies()
    {
        $shops = Mage::getModel('ayalineshop/shop')->getCollection()->addFieldToFilter('used_for_shipping', self:: USED_FOR_SHIPPING_TRUE);
        foreach ($shops as $_shop) {
            $this->_options[] = array(
                'value' => $_shop->getId(),
                'label' => $_shop->getTitle() . ' '
                    . $_shop->getStreet1() . ' ' . $_shop->getStreet2() . ' '
                    . $_shop->getPostcode() . ' ' . $_shop->getCity() . ' '
                    . Mage::getModel('directory/country')->load($_shop->getCountryId())->getName(),
            );
        }

        return $this->_options;
    }

    public function getAgencyById($id)
    {
        if ($this->_shop == null) {
            $this->_shop = Mage::getModel('ayalineshop/shop')->load($id);
        }

        return $this->_shop;
    }

    public function getGroupMarkerUrl($groupId)
    {
        $group = Mage::getSingleton('ayalineshop/shop_group')->load($groupId);
        $markerFile = (Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . Ayaline_Shop_Model_Shop_Group::PICTO_PATH_FOLDER . DS . $group->getOldMarker());
        if ($group->getId() && file_exists($markerFile) && !is_dir($markerFile)) {
            return $group->getMarker();
        }

        return false;
    }

    public function getShopMarkerUrl($shop)
    {
        $markerFile = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . Ayaline_Shop_Model_Shop::PICTO_PATH_FOLDER . DS . ($shop->getOldMarker() ? $shop->getOldMarker() : $shop->getMarker());
        if (file_exists($markerFile) && !is_dir($markerFile)) {
            return Mage::getBaseUrl('media') . Ayaline_Shop_Model_Shop::PICTO_PATH_FOLDER . DS . $shop->getMarker();
        }

        return false;
    }
}