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
//Use of a collection to avoid performance issues.
$collection = Mage::getModel('core/store')->getCollection()->addFieldToFilter('code', 'sabre_fr_french');
$frenchStoreId = $collection->getFirstItem()->getId();

//Correction of an error in last Setup.
$configValue = Mage::getStoreConfig('carriers/owebiashipping2/config', $frenchStoreId);
$frObjectUPS = str_replace('"label": "Livraison par UPS",',
    '"label": "National",', $configValue);
$this->setConfigData('carriers/owebiashipping2/config', $frObjectUPS, 'stores', $frenchStoreId);

//UPS -> Livraison
$this->setConfigData('carriers/owebiashipping2/title', "Livraison par UPS", 'stores', $frenchStoreId);

//Sabre shop
//Title
$this->setConfigData('carriers/sabreshop/title', "Retrait en boutique", 'stores', $frenchStoreId);
//Label
$this->setConfigData('carriers/sabreshop/name', "Retrait en boutique", 'stores', $frenchStoreId);

