<?php
$installer = $this;

/* @var $installer Mage_Sales_Model_Entity_Setup */

$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'amount_min',
    'double(5,2)', 0
);
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'amount_max',
    'double(5,2)', 0
);
$installer->endSetup();

