<?php

/** @var $this Mage_Core_Model_Resource_Setup */

$websiteFranceCode = "sabre_fr";
$websiteAllemagneCode = "sabre_de";
$websiteHollandeCode = "sabre_nl";
$websiteAngleterreCode = "sabre_uk";
$websiteBelgiqueCode = "sabre_be";
$websiteItalieCode = "sabre_it";
$websiteUsaCode = "sabre_com";

// Websites
$websites = array(
    'FR' => Mage::getModel('core/website')->load($websiteFranceCode, 'code'),
    'DE' => Mage::getModel('core/website')->load($websiteAllemagneCode, 'code'),
    'NL' => Mage::getModel('core/website')->load($websiteHollandeCode, 'code'),
    'UK' => Mage::getModel('core/website')->load($websiteAngleterreCode, 'code'),
    'BE' => Mage::getModel('core/website')->load($websiteBelgiqueCode, 'code'),
    'IT' => Mage::getModel('core/website')->load($websiteItalieCode, 'code'),
    'US' => Mage::getModel('core/website')->load($websiteUsaCode, 'code'),
);

// Pays autorisés
$this->setConfigData('sabre_georedirect/general/allowedcountries', 'FR,GF,GP,MQ,RE,PM,YT,BL,MF,TF,WF,PF,NC', 'websites', $websites['FR']->getId());
$this->setConfigData('sabre_georedirect/general/allowedcountries', 'DE,AT', 'websites', $websites['DE']->getId());
$this->setConfigData('sabre_georedirect/general/allowedcountries', 'NL', 'websites', $websites['NL']->getId());
$this->setConfigData('sabre_georedirect/general/allowedcountries', 'GB', 'websites', $websites['UK']->getId());
$this->setConfigData('sabre_georedirect/general/allowedcountries', 'BE,LU', 'websites', $websites['BE']->getId());
$this->setConfigData('sabre_georedirect/general/allowedcountries', 'IT', 'websites', $websites['IT']->getId());
$this->setConfigData('sabre_georedirect/general/allowedcountries', 'US', 'websites', $websites['US']->getId());


