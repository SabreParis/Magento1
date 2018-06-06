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

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'a_text_color', array(
    'group'            => 'Display Settings',
    'input'            => 'text',
    'type'             => 'text',
    'label'            => 'Text color',
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'backend'          => '',
    'visible'          => true,
    'visible_on_front' => true,
    'required'         => false,
    'note'             => '3 or 6 digits in hexadecimal',
    ));