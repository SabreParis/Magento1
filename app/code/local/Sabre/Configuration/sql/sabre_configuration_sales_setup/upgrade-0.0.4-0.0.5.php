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

/* @var $this Mage_Sales_Model_Resource_Setup */


$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('sales/quote'), 'shop_id', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'comment' => 'sabre: shop id',
    'nullable'  => true,
));
$installer->getConnection()->addColumn($installer->getTable('sales/order'), 'shop_id', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'nullable'  => true,
    'comment' => 'sabre: shop id',
));

$installer->endSetup();