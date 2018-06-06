<?php
/**
 * created: 2015
 *
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/** @var $this Mage_Catalog_Model_Resource_Setup */

$productEntityTypeId = $this->getEntityTypeId(Mage_Catalog_Model_Product::ENTITY);
$defaultAttributeSetId = $this->getAttributeSetId($productEntityTypeId, 'Default');
$aArticlePosition = $this->getAttribute($productEntityTypeId, 'a_article', 'sort_order');
$aModelPosition = $this->getAttribute($productEntityTypeId, 'a_model', 'sort_order');

// new attribute sets: cutlery / porcelain / accessory
$attributeSets = [
    Sabre_Catalog_Helper_Data::ATTRIBUTE_SET_CUTLERY   => 'Cutlery',
    Sabre_Catalog_Helper_Data::ATTRIBUTE_SET_PORCELAIN => 'Porcelain',
    Sabre_Catalog_Helper_Data::ATTRIBUTE_SET_ACCESSORY => 'Accessory',
];

// create 3 attribute sets based on default
foreach ($attributeSets as $attributeSetCode => $attributeSetName) {
    $attributeSets = Mage::getResourceModel('eav/entity_attribute_set_collection');
    $attributeSets->addFieldToFilter('entity_type_id', ['eq' => $productEntityTypeId]);
    $attributeSets->addFieldToFilter('attribute_set_code', ['eq' => $attributeSetCode]);
    $attributeSets->setPageSize(1);

    if (!$attributeSets->getSize()) {
        $attributeSet = Mage::getModel('eav/entity_attribute_set');

        $attributeSet->setAttributeSetName($attributeSetName)
                     ->setAttributeSetCode($attributeSetCode)
                     ->setEntityTypeId($productEntityTypeId)
                     ->save();

        $attributeSet->initFromSkeleton($defaultAttributeSetId);
        $attributeSet->save();

        // retrieve attribute group id "General" for this attribute set
        $attributeGroupId = $this->getAttributeGroupId($productEntityTypeId, $attributeSet->getId(), 'General');

        // create additional attributes article and model for each attribute set
        //  this attributes are filterable (with no results) on layer, but not used on search
        $this->addAttribute(
            $productEntityTypeId,
            "a_article_{$attributeSetCode}",
            [
                'label'                      => "Article ({$attributeSetName})",
                'input'                      => 'select',
                'type'                       => 'int',
                'backend'                    => null,
                'source'                     => null,
                'table'                      => null,
                'frontend'                   => null,
                'frontend_class'             => null,
                'required'                   => 1,
                'user_defined'               => 1,
                'default'                    => null,
                'unique'                     => 0,
                'note'                       => "type d'article",
                'visible'                    => 1,
                'searchable'                 => 0,
                'filterable'                 => 2, // filterable with no results
                'comparable'                 => 0,
                'visible_on_front'           => 0,
                'wysiwyg_enabled'            => 0,
                'is_html_allowed_on_front'   => 0,
                'visible_in_advanced_search' => 0,
                'filterable_in_search'       => 0,
                'used_in_product_listing'    => 1,
                'used_for_sort_by'           => 0,
                'apply_to'                   => null,
                'position'                   => ++$aArticlePosition,
                'sort_order'                 => $aArticlePosition,
                'is_configurable'            => 0,
                'used_for_promo_rules'       => 0,
                'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            ]
        );

        $attributeId = $this->getAttributeId($productEntityTypeId, "a_article_{$attributeSetCode}");
        $this->addAttributeToSet($productEntityTypeId, $attributeSet->getId(), $attributeGroupId, $attributeId);


        $this->addAttribute(
            $productEntityTypeId,
            "a_model_{$attributeSetCode}",
            [
                'label'                      => "Modèle ({$attributeSetName})",
                'input'                      => 'select',
                'type'                       => 'int',
                'backend'                    => null,
                'source'                     => null,
                'table'                      => null,
                'frontend'                   => null,
                'frontend_class'             => null,
                'required'                   => 1,
                'user_defined'               => 1,
                'default'                    => null,
                'unique'                     => 0,
                'note'                       => '',
                'visible'                    => 1,
                'searchable'                 => 0,
                'filterable'                 => 2, // filterable with no results
                'comparable'                 => 0,
                'visible_on_front'           => 0,
                'wysiwyg_enabled'            => 0,
                'is_html_allowed_on_front'   => 0,
                'visible_in_advanced_search' => 0,
                'filterable_in_search'       => 0,
                'used_in_product_listing'    => 1,
                'used_for_sort_by'           => 0,
                'apply_to'                   => null,
                'position'                   => ++$aModelPosition,
                'sort_order'                 => $aModelPosition,
                'is_configurable'            => 0,
                'used_for_promo_rules'       => 0,
                'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            ]
        );

        $attributeId = $this->getAttributeId($productEntityTypeId, "a_model_{$attributeSetCode}");
        $this->addAttributeToSet($productEntityTypeId, $attributeSet->getId(), $attributeGroupId, $attributeId);

    }
}

// update a_article & a_model filterable state (no on layer / yes on search)
foreach (['a_article', 'a_model'] as $attributeCode) {
    $this->updateAttribute($productEntityTypeId, $attributeCode, 'is_filterable', 0);
    $this->updateAttribute($productEntityTypeId, $attributeCode, 'is_filterable_in_search', 1);
}
