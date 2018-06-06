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


$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'a_show_is_set', array(
    'group'         => 'Display Settings',
    'input'         => 'select',
    'type'          => 'int',
    'label'         => 'Afficher les set ',
    'backend'       => '',
    'visible'       => true,
    'source'        => 'eav/entity_attribute_source_boolean',
    'required'      => false,
    'position'      => 1005,
    'sort_order'    => 1009,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));


$categoryEntityTypeId = $this->getEntityTypeId(Mage_Catalog_Model_Category::ENTITY);

// create additional attributes article and model for each attribute set
$this->addAttribute(
    $categoryEntityTypeId,
    "a_article",
    [
        'label'                      => 'Article',
        'input'                      => 'select',
        'type'                       => 'int',
        'input_renderer'             => 'sabre_adminhtml/catalog_category_helper_article',
        'backend'                    => null,
        'source'                     => null,
        'table'                      => null,
        'frontend'                   => null,
        'frontend_class'             => null,
        'required'                   => 0,
        'user_defined'               => 1,
        'default'                    => null,
        'unique'                     => 0,
        'visible'                    => 1,
        'apply_to'                   => null,
        'position'                   => 1009,
        'sort_order'                 => 1010,
        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'group'                      => 'Display Settings',
    ]
);


/** @var $this Mage_Catalog_Model_Resource_Setup */

$this->addAttribute(
    $categoryEntityTypeId,
    "a_model",
    [
        'label'                      => 'Modèle',
        'input'                      => 'select',
        'type'                       => 'int',
        'input_renderer'             => 'sabre_adminhtml/catalog_category_helper_model',
        'backend'                    => null,
        'source'                     => 'core/design_source_design',
        'table'                      => null,
        'frontend'                   => null,
        'frontend_class'             => null,
        'required'                   => 0,
        'user_defined'               => 1,
        'default'                    => null,
        'unique'                     => 0,
        'visible'                    => 1,
        'apply_to'                   => null,
        'position'                   => 1011,
        'sort_order'                 => 1011,
        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'group'                      => 'Display Settings',
    ]
);

$this->addAttribute(
    $categoryEntityTypeId,
    "a_filter_color",
    [
        'label'                      => 'Couleur',
        'input'                      => 'select',
        'type'                       => 'int',
        'input_renderer'             => 'sabre_adminhtml/catalog_category_helper_color',
        'backend'                    => null,
        'source'                     => null,
        'table'                      => null,
        'frontend'                   => null,
        'frontend_class'             => null,
        'required'                   => 0,
        'user_defined'               => 1,
        'default'                    => null,
        'unique'                     => 0,
        'visible'                    => 1,
        'apply_to'                   => null,
        'position'                   => 1013,
        'sort_order'                 => 1013,
        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'group'                      => 'Display Settings',
    ]
);



