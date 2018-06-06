<?php

/**
 * created : 02/10/2015
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
    ->addColumn($installer->getTable('ayalinebillboard/billboard'),'additional_content', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'additional content'
    ));

$installer->endSetup();
