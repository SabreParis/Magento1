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

/** @var $this Mage_Core_Model_Resource_Setup */

// allow block catalog/navigation
$this->getConnection()->delete($this->getTable('admin/permission_block'), ['block_name = ?' => 'catalog/navigation']);

Mage::getModel('admin/block')
    ->setData(['block_name' => 'catalog/navigation', 'is_allowed' => 1])
    ->save();
