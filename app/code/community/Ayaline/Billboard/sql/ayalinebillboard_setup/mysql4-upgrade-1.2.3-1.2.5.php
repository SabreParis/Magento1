<?php
/**
 * created : 7 mai 2012
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/* @var $this Mage_Core_Model_Resource_Setup */

$this->startSetup();

$this->getConnection()->addColumn($this->getTable('ayalinebillboard/billboard'), 'widget_position', 'INT(11) NOT NULL DEFAULT 0');

$this->endSetup();