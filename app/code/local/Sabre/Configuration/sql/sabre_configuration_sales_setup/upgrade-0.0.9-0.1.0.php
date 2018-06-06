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

/**
 * Add Attributes "erp_status", "erp_error_msg", "erp_export_date" to sales_flat_order table, 
 * For Dataflow Export uses
 */
$installer = $this;
$installer->startSetup();

$installer
    ->getConnection()
    ->addColumn($installer->getTable('sales/order'), 'erp_status', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length' => '255',
        'default' => 'pending',
        'nullable' => true,
        'comment' => "Sabre : Dataflow - Export status (pending, success, error)",
    ));

$installer
    ->getConnection()
    ->addColumn($installer->getTable('sales/order'), 'erp_error_msg', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'comment' => "Sabre : Dataflow - Export error msg",
    ));

$installer
    ->getConnection()
    ->addColumn($installer->getTable('sales/order'), 'erp_export_date', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DATETIME,
        'nullable' => true,
        'comment' => "Sabre : Dataflow - Export Date",
    ));

$installer->endSetup();
