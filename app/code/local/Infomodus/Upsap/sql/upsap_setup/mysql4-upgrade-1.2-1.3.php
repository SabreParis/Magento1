<?php
$installer = $this;

/* @var $installer Mage_Sales_Model_Entity_Setup */

$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'negotiated',
    'TINYINT(1) DEFAULT 0', 0
);
$installer->endSetup();

