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
/* @var $this Mage_Catalog_Model_Resource_Setup */

$filtrableAttributes = array('a_filter_color', 'a_model', 'a_article');
$usedInProductListing = array('a_filter_color', 'a_model', 'a_article', 'color', 'a_is_set');

/* @var $attributes Mage_Catalog_Model_Resource_Product_Attribute_Collection */
$attributes = Mage::getResourceModel('catalog/product_attribute_collection')
        ->getItems();

/* @var $_attribute Mage_Catalog_Model_Resource_Eav_Attribute */
foreach ($attributes as $_attribute) {
    if (in_array($_attribute->getAttributeCode(), $filtrableAttributes)) {
        $_attribute->setData('is_filterable', 2);
        $_attribute->setData('is_filterable_in_search', 1);
    } else {
        $_attribute->setData('is_filterable', 0);
        $_attribute->setData('is_filterable_in_search', 0);
    }
    if(in_array($_attribute->getAttributeCode(), $usedInProductListing)) {
        $_attribute->setData('used_in_product_listing', 1);
    }
    $_attribute->save();
}

