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
$defaultWebsite = Mage::getModel('core/website')->load($websiteFranceCode, 'code');
$websites = array(
    'DE' => Mage::getModel('core/website')->load($websiteAllemagneCode, 'code'),
    'NL' => Mage::getModel('core/website')->load($websiteHollandeCode, 'code'),
    'UK' => Mage::getModel('core/website')->load($websiteAngleterreCode, 'code'),
    'BE' => Mage::getModel('core/website')->load($websiteBelgiqueCode, 'code'),
    'IT' => Mage::getModel('core/website')->load($websiteItalieCode, 'code'),
    'US' => Mage::getModel('core/website')->load($websiteUsaCode, 'code'),
);

/**
 * Gestion des Homes
 */

// Récupération de la HOME par défaut
$homepages = Mage::getModel("cms/page")->getCollection();
$homepages->addStoreFilter($defaultWebsite->getDefaultStore());
$homepages->addFieldToFilter("identifier", "home");
/* @var $homepageFR Mage_Cms_Model_Page */
$homepageFR = $homepages->getFirstItem();
if ($homepageFR && $homepageFR->getId()) {

    // Création des nouvelles homes
    foreach ($websites as $_website) {
        /* @var $_website Mage_Core_Model_Website */
        $stores = $_website->getStores();
        foreach ($stores as $_store) {

            /* @var $_store Mage_Core_Model_Store */
            /* @var $homepages Mage_Cms_Model_Resource_Page_Collection */
            $homepages = Mage::getModel("cms/page")->getCollection();
            $homepages->addStoreFilter($_store);
            $homepages->addFieldToFilter("identifier", "home");

            if ($homepages->count()==0) {
                /* @var $homepage Mage_Cms_Model_Page */
                $homepage = $homepageFR;
                $homepage->unsetData("page_id");
                $homepage->setStores($_store->getId());
                $websiteName = strtoupper(substr($_website->getCode(), strrpos($_website->getCode(), '_')+1));
                $storeName = strtoupper(substr($_store->getCode(), strrpos($_store->getCode(), '_')+1));
                $homepage->setTitle("Home Website $websiteName ($storeName)");
                $homepage->save();
            }

        }
    }

}

