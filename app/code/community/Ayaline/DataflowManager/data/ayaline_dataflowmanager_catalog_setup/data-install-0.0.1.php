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

$this->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'ayaline_uniq_id',
    array(
        'label'                      => 'aYaline: Uniq Product Id',
        'type'                       => 'static',
        'input'                      => 'text',
        'backend'                    => null,
        'table'                      => null,
        'frontend'                   => null,
        'frontend_class'             => null,
        'source'                     => null,
        'required'                   => false,
        'user_defined'               => 0,
        'default'                    => null,
        'unique'                     => 1,
        'note'                       => null,
        'input_renderer'             => null,
        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'                    => 0,
        'searchable'                 => 0,
        'filterable'                 => 0,
        'comparable'                 => 0,
        'visible_on_front'           => 0,
        'wysiwyg_enabled'            => 0,
        'is_html_allowed_on_front'   => 0,
        'visible_in_advanced_search' => 0,
        'filterable_in_search'       => 0,
        'used_in_product_listing'    => 0,
        'used_for_sort_by'           => 0,
        'apply_to'                   => null,
        'is_configurable'            => 0,
        'used_for_promo_rules'       => 0,
        'position'                   => 100000,
        'sort_order'                 => 100000,
    )
);