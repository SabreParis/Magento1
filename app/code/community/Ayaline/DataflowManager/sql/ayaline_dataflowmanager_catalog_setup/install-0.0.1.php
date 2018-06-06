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

$this->startSetup();

$this->getConnection()->addColumn(
    $this->getTable('catalog/product'),
    'ayaline_uniq_id',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'   => 255,
        'nullable' => true,
        'comment'  => 'aYaline: Uniq Product Id',
    )
);

$this->endSetup();