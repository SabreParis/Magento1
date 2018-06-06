<?php
/**
 * created : 10/03/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Shop
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */


$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('ayalineshop/shop_group')} MODIFY name varchar(50);
");

$installer->endSetup();