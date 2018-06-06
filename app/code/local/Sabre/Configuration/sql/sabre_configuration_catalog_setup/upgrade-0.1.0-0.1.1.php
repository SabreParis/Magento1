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

$this->startSetup();

// add column on attribute set table
$this->getConnection()->addColumn(
    $this->getTable('eav/attribute_set'),
    'attribute_set_code',
    [
        'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'  => '255',
        'default' => 'default',
        'comment' => 'aYaline: attribute set code',
    ]
);


$this->endSetup();