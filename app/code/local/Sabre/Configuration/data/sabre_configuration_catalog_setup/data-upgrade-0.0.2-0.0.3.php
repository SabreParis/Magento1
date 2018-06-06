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

/** @var $this Mage_Catalog_Model_Resource_Setup */

// use Color as real color and a_filter_color as color for layered navigation

$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();

$aFilterColorPosition = $this->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_filter_color', 'sort_order');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'color', 'position', $aFilterColorPosition);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'color', 'sort_order', $aFilterColorPosition);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'color', 'is_required', 1);

$colorAttributeId = $this->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'color');
$attributeSetId = $this->getAttributeSetId(Mage_Catalog_Model_Product::ENTITY, 'Default');
$attributeGroupId = $this->getAttributeGroupId(Mage_Catalog_Model_Product::ENTITY, $attributeSetId, 'General');

$this->addAttributeToSet(Mage_Catalog_Model_Product::ENTITY, $attributeSetId, $attributeGroupId, $colorAttributeId, $aFilterColorPosition);

// create all color option
$colorOptions = [
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'marron',
        $frenchStoreId                      => 'Marron',
        $englishStoreId                     => 'Marron',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'rouge',
        $frenchStoreId                      => 'Rouge',
        $englishStoreId                     => 'Rouge',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'orange',
        $frenchStoreId                      => 'Orange',
        $englishStoreId                     => 'Orange',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'jaune',
        $frenchStoreId                      => 'Jaune',
        $englishStoreId                     => 'Jaune',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'vert',
        $frenchStoreId                      => 'Vert',
        $englishStoreId                     => 'Vert',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bleu',
        $frenchStoreId                      => 'Bleu',
        $englishStoreId                     => 'Bleu',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'rose',
        $frenchStoreId                      => 'Rose',
        $englishStoreId                     => 'Rose',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'gris',
        $frenchStoreId                      => 'Gris',
        $englishStoreId                     => 'Gris',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bois1',
        $frenchStoreId                      => 'Bois1',
        $englishStoreId                     => 'Bois1',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bois2',
        $frenchStoreId                      => 'Bois2',
        $englishStoreId                     => 'Bois2',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bois3',
        $frenchStoreId                      => 'Bois3',
        $englishStoreId                     => 'Bois3',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bois4',
        $frenchStoreId                      => 'Bois4',
        $englishStoreId                     => 'Bois4',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bois5',
        $frenchStoreId                      => 'Bois5',
        $englishStoreId                     => 'Bois5',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bois6',
        $frenchStoreId                      => 'Bois6',
        $englishStoreId                     => 'Bois6',
    ],
];


foreach ($colorOptions as $_colorOption) {
    $this->addAttributeOption(['attribute_id' => $colorAttributeId, 'value' => [$_colorOption]]);
}

