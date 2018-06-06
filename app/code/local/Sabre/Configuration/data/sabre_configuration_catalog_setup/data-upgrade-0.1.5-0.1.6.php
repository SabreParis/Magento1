<?php
/**
 * created: 2016
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2016 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/** @var $this Mage_Catalog_Model_Resource_Setup */
$this->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_size');

$position = 600;

$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_size', array(
    'label'                      => 'Taille',
    'input'                      => 'text',
    'type'                       => 'varchar',
    'backend'                    => null,
    'source'                     => null,
    'table'                      => null,
    'frontend'                   => null,
    'frontend_class'             => null,
    'required'                   => 0,
    'user_defined'               => 1,
    'default'                    => '',
    'unique'                     => 0,
    'note'                       => '',
    'visible'                    => 1,
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
    'position'                   => 600,
    'sort_order'                 => 600,
    'is_configurable'            => 0,
    'group'                      => 'General',
    'used_for_promo_rules'       => 0,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));
