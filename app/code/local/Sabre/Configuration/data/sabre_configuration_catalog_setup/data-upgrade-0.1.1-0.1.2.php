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

$categoryEntityTypeId = $this->getEntityTypeId(Mage_Catalog_Model_Category::ENTITY);

// create additional attributes article and model for each attribute set
//  this attributes are filterable (with no results) on layer, but not used on search
$this->addAttribute(
    $categoryEntityTypeId,
    "related_product_attribute_set",
    [
        'label'                      => "Jeu d'attributs produit associé",
        'input'                      => 'select',
        'type'                       => 'int',
        'input_renderer'             => 'sabre_adminhtml/catalog_category_helper_relatedProductAttributeSet',
        'backend'                    => null,
        'source'                     => null,
        'table'                      => null,
        'frontend'                   => null,
        'frontend_class'             => null,
        'required'                   => 1,
        'user_defined'               => 1,
        'default'                    => null,
        'unique'                     => 0,
        'visible'                    => 1,
        'apply_to'                   => null,
        'position'                   => 1000,
        'sort_order'                 => 1000,
        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'group'                      => 'Display Settings',
    ]
);
