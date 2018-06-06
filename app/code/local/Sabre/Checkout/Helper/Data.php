<?php

/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Checkout_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getShippingMethodImage($rateCode)
    {
        $pathTo = 'images/mode_shipping/';
        // first in media
        if (file_exists(Mage::getBaseDir('media') . DS . $pathTo . $rateCode . '.png')) {
            return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "{$pathTo}{$rateCode}.png";
        }

        // second in skin
        if (file_exists(Mage::getDesign()->getSkinBaseDir() . DS . $pathTo . $rateCode . '.png')) {
            return Mage::getDesign()->getSkinUrl("{$pathTo}{$rateCode}.png");
        }

        // else default
        return Mage::getDesign()->getSkinUrl("{$pathTo}default.png");
    }

    public function getEnableExpressOrderLabel($product)
    {
        $label = 'Complete my service';

        $productType = Mage::helper('sabre_catalog')->getProductAttributeSetCode($product);
        if ($productType == Sabre_Catalog_Helper_Data::ATTRIBUTE_SET_CUTLERY) {
            $label = 'Complete my housekeeping';
        }

        return $this->__($label);
    }

    public function getUpdateExpressOrderLabel($product)
    {
        $label = 'Update my service';

        $productType = Mage::helper('sabre_catalog')->getProductAttributeSetCode($product);
        if ($productType == Sabre_Catalog_Helper_Data::ATTRIBUTE_SET_CUTLERY) {
            $label = 'Update my housekeeping';
        }

        return $this->__($label);
    }

}