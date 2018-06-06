<?php
$installer = $this;

/* @var $installer Mage_Sales_Model_Entity_Setup */

$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'tax',
    'INT(2) DEFAULT 0', 0
);
$installer->endSetup();

