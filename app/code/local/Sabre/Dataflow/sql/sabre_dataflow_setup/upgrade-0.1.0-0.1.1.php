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

// add column on shop table
$connexion = $this->getConnection();
$table = $this->getTable('ayalineshop/shop');

if (!$connexion->tableColumnExists($table, "sabre_code")) {

    $connexion->addColumn(
        $table,
        'sabre_code',
        [
            'type'    => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'unsigned'  => true,
            'nullable'  => true,
            'default' => null,
            'comment' => 'aYaline: Sabre ID',
        ]
    );

    $connexion->addIndex(
        $table,
        $connexion->getIndexName($table, "sabre_code"),
        "sabre_code"
    );

}

$this->endSetup();