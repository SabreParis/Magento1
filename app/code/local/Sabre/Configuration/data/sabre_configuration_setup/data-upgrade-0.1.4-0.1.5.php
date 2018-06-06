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


//sabre shop


/** @var $this Mage_Core_Model_Resource_Setup */

$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();



$this->setConfigData('carriers/owebiashipping4/moreinfo',"Your order delivered by UPS Access Point with delivery against signature. Placed before 13h will be shipped that day or the next day if the product were indicated « in stock » on our website at the time of the order. Time: 24 / 48h after shipping",'stores', $englishStoreId);

$this->setConfigData('carriers/owebiashipping4/moreinfo',"Votre commande livré en point relais UPS avec remise contre signature. Passée avant 13h, elle sera expédiée dans la journée ou le lendemain si le produit était indiqué « En stock » sur notre site au moment de la commande. Délais : 24/48h après expédition",'stores', $frenchStoreId);


$this->setConfigData('carriers/owebiashipping2/moreinfo',"Your order delivered by UPS with delivery against signature.. Placed before 13h will be shipped that day or the next day if the product were indicated « in stock » on our website at the time of the order. Time: 24 / 48h after shipping",'stores', $englishStoreId);

$this->setConfigData('carriers/owebiashipping2/moreinfo',"Votre commande livré en  UPS avec remise contre signature. Passée avant 13h, elle sera expédiée dans la journée ou le lendemain si le produit était indiqué « En stock » sur notre site au moment de la commande. Délais : 24/48h après expédition",'stores', $frenchStoreId);
