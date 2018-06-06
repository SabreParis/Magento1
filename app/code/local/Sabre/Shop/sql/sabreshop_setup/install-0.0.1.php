<?php

/**
 * created : 12/10/2015
 *
 * @category Ayaline
 * @package Ayaline_Billboard
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */


$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('ayalineshop/shop'),'used_for_shipping', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
        'nullable'  => false,
        'default' => 0,
        'comment'   => 'used for shipping'
    ));

$installer->getConnection()
    ->addColumn($installer->getTable('ayalineshop/shop_group'),'marker', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'length'    =>255,
        'comment'   => 'marker'
    ));

$installer->endSetup();
