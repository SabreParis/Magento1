<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();
$installer->run("

ALTER TABLE `{$installer->getTable('sales/order')}` ADD `tft_from` int( 1 ) NOT NULL ;
ALTER TABLE `{$installer->getTable('sales/order')}` ADD `tft_order_number` VARCHAR( 255 ) ;
ALTER TABLE `{$installer->getTable('sales/order')}` ADD `tft_order_marketplace_source` VARCHAR( 255 );

");
$installer->endSetup();
