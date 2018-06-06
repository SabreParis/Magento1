<?php

/** @var $this Mage_Core_Model_Resource_Setup */

/***********************
 * Gestion des pays
************************/

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

// Stores
$stores = array(
    'sabre_de_deutsch' => Mage::getModel('core/store')->load('sabre_de_deutsch', 'code'),
    'sabre_de_english' => Mage::getModel('core/store')->load('sabre_de_english', 'code'),
    'sabre_nl_dutch' => Mage::getModel('core/store')->load('sabre_nl_dutch', 'code'),
    'sabre_nl_english' => Mage::getModel('core/store')->load('sabre_nl_english', 'code'),
    'sabre_uk_english' => Mage::getModel('core/store')->load('sabre_uk_english', 'code'),
    'sabre_uk_francais' => Mage::getModel('core/store')->load('sabre_uk_francais', 'code'),
    'sabre_be_dutch' => Mage::getModel('core/store')->load('sabre_be_dutch', 'code'),
    'sabre_be_francais' => Mage::getModel('core/store')->load('sabre_be_francais', 'code'),
    'sabre_it_italiano' => Mage::getModel('core/store')->load('sabre_it_italiano', 'code'),
    'sabre_it_english' => Mage::getModel('core/store')->load('sabre_it_english', 'code'),
    'sabre_com_english' => Mage::getModel('core/store')->load('sabre_com_english', 'code'),
    'sabre_com_francais' => Mage::getModel('core/store')->load('sabre_com_francais', 'code'),
);

// Pays par défaut
$this->setConfigData('general/country/default', 'FR', 'websites', $websites['FR']->getId());
$this->setConfigData('general/country/default', 'DE', 'websites', $websites['DE']->getId());
$this->setConfigData('general/country/default', 'NL', 'websites', $websites['NL']->getId());
$this->setConfigData('general/country/default', 'GB', 'websites', $websites['UK']->getId());
$this->setConfigData('general/country/default', 'BE', 'websites', $websites['BE']->getId());
$this->setConfigData('general/country/default', 'IT', 'websites', $websites['IT']->getId());
$this->setConfigData('general/country/default', 'US', 'websites', $websites['US']->getId());

// Pays autorisés
$this->setConfigData('general/country/allow', 'FR,GF,GP,MQ,RE,PM,YT,BL,MF,TF,WF,PF,NC', 'websites', $websites['FR']->getId());
$this->setConfigData('general/country/allow', 'DE,AT', 'websites', $websites['DE']->getId());
$this->setConfigData('general/country/allow', 'NL', 'websites', $websites['NL']->getId());
$this->setConfigData('general/country/allow', 'GB', 'websites', $websites['UK']->getId());
$this->setConfigData('general/country/allow', 'BE,LU', 'websites', $websites['BE']->getId());
$this->setConfigData('general/country/allow', 'IT', 'websites', $websites['IT']->getId());
$this->setConfigData('general/country/allow', 'US', 'websites', $websites['US']->getId());

// Gestion des langues
$this->setConfigData('general/locale/code', 'de_DE', 'websites', $websites['DE']->getId());
$this->setConfigData('general/locale/code', 'en_US', 'stores', $stores['sabre_de_english']->getId());
$this->setConfigData('general/locale/code', 'nl_NL', 'websites', $websites['NL']->getId());
$this->setConfigData('general/locale/code', 'en_US', 'stores', $stores['sabre_nl_english']->getId());
$this->setConfigData('general/locale/code', 'en_GB', 'websites', $websites['UK']->getId());
$this->setConfigData('general/locale/code', 'fr_FR', 'stores', $stores['sabre_uk_francais']->getId());
$this->setConfigData('general/locale/code', 'nl_NL', 'websites', $websites['BE']->getId());
$this->setConfigData('general/locale/code', 'fr_FR', 'stores', $stores['sabre_be_francais']->getId());
$this->setConfigData('general/locale/code', 'it_IT', 'websites', $websites['IT']->getId());
$this->setConfigData('general/locale/code', 'en_US', 'stores', $stores['sabre_it_english']->getId());
$this->setConfigData('general/locale/code', 'en_US', 'websites', $websites['US']->getId());
$this->setConfigData('general/locale/code', 'fr_FR', 'stores', $stores['sabre_com_francais']->getId());

// Estimation de calcul de la taxe par défaut
$this->setConfigData('tax/defaults/country', 'DE', 'websites', $websites['DE']->getId());
$this->setConfigData('tax/defaults/country', 'NL', 'websites', $websites['NL']->getId());
$this->setConfigData('tax/defaults/country', 'GB', 'websites', $websites['UK']->getId());
$this->setConfigData('tax/defaults/country', 'BE', 'websites', $websites['BE']->getId());
$this->setConfigData('tax/defaults/country', 'IT', 'websites', $websites['IT']->getId());
$this->setConfigData('tax/defaults/country', 'US', 'websites', $websites['US']->getId());

// Gestion des prix et des devises
$this->setConfigData('catalog/price/scope', 1, 'default');
$this->setConfigData('currency/options/base', 'EUR', 'global');
$this->setConfigData('currency/options/base', 'GBP', 'websites', $websites['UK']->getId());
$this->setConfigData('currency/options/base', 'USD', 'websites', $websites['US']->getId());
$this->setConfigData('currency/options/default', 'EUR', 'global');
$this->setConfigData('currency/options/default', 'GBP', 'websites', $websites['UK']->getId());
$this->setConfigData('currency/options/default', 'USD', 'websites', $websites['US']->getId());
$this->setConfigData('currency/options/allow', 'EUR', 'global');
$this->setConfigData('currency/options/allow', 'GBP', 'websites', $websites['UK']->getId());
$this->setConfigData('currency/options/allow', 'USD', 'websites', $websites['US']->getId());

// Gestion des URLs
$this->setConfigData('web/unsecure/base_url', 'http://www.sabre-de.dev/', 'websites', $websites['DE']->getId());
$this->setConfigData('web/secure/base_url', 'http://www.sabre-de.dev/', 'websites', $websites['DE']->getId());
$this->setConfigData('web/unsecure/base_url', 'http://www.sabre-nl.dev/', 'websites', $websites['NL']->getId());
$this->setConfigData('web/secure/base_url', 'http://www.sabre-nl.dev/', 'websites', $websites['NL']->getId());
$this->setConfigData('web/unsecure/base_url', 'http://www.sabre-uk.dev/', 'websites', $websites['UK']->getId());
$this->setConfigData('web/secure/base_url', 'http://www.sabre-uk.dev/', 'websites', $websites['UK']->getId());
$this->setConfigData('web/unsecure/base_url', 'http://www.sabre-be.dev/', 'websites', $websites['BE']->getId());
$this->setConfigData('web/secure/base_url', 'http://www.sabre-be.dev/', 'websites', $websites['BE']->getId());
$this->setConfigData('web/unsecure/base_url', 'http://www.sabre-it.dev/', 'websites', $websites['IT']->getId());
$this->setConfigData('web/secure/base_url', 'http://www.sabre-it.dev/', 'websites', $websites['IT']->getId());
$this->setConfigData('web/unsecure/base_url', 'http://www.sabre-com.dev/', 'websites', $websites['US']->getId());
$this->setConfigData('web/secure/base_url', 'http://www.sabre-com.dev/', 'websites', $websites['US']->getId());
