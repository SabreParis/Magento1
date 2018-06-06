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
 * Add product attributes to sales entities
 * Atributes : a_is_set, a_article, a_model, color
 */

$installer = $this;
$installer->startSetup();

$attributes = array(
    'a_is_set' => array(
        'code' => 'a_is_set',
        'label' => 'est un set',
        'column_type' => Varien_Db_Ddl_Table::TYPE_SMALLINT
    ),
    'a_article' => array(
        'code' => 'a_article',
        'label' => 'type article',
        'column_type' => Varien_Db_Ddl_Table::TYPE_INTEGER
    ),
    'a_model' => array(
        'code' => 'a_model',
        'label' => 'model',
        'column_type' => Varien_Db_Ddl_Table::TYPE_INTEGER
    ),
    'color' => array(
        'code' => 'color',
        'label' => 'couleur',
        'column_type' => Varien_Db_Ddl_Table::TYPE_INTEGER
    ),
);
$tables = array(
    $installer->getTable('sales/quote_item'),
    $installer->getTable('sales/order_item'),
    $installer->getTable('sales/shipment_item'),
    $installer->getTable('sales/invoice_item'),
    $installer->getTable('sales/creditmemo_item')
);

foreach ($attributes as $attribute) {
    $attrCode = $attribute['code'];
    $attrComment = $attribute['label'];
    $attrColType = $attribute['column_type'];
    foreach ($tables as $table) {
        $installer->getConnection()->addColumn($table, $attrCode, array(
            'type' => $attrColType,
            'comment' => "Sabre : $attrComment",
            'nullable' => true,
        ));
    }
}

$installer->endSetup();
