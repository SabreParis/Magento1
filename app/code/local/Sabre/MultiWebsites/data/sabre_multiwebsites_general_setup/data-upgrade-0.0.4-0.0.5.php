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
 * Gestion des blocs :
 *  "reseaux-sociaux"
 *  "footer_links_company"
 */

$tbBlocks = array(
    "reseaux-sociaux"=>"Réseau sociaux",
    "footer_links_company"=>"Footer Links Company"
);

foreach ($tbBlocks as $blockCode => $blockName) {

    // Récupération du bloc "reseaux sociaux" par défaut
    $blockList = Mage::getModel("cms/block")->getCollection();
    $blockList->addStoreFilter($defaultWebsite->getDefaultStore());
    $blockList->addFieldToFilter("identifier", $blockCode);
    /* @var $blockFR Mage_Cms_Model_Block */
    $blockFR = $blockList->getFirstItem();
    if ($blockFR && $blockFR->getId()) {

        // Création des nouveaux blocs
        foreach ($websites as $_website) {
            /* @var $_website Mage_Core_Model_Website */
            $stores = $_website->getStores();
            foreach ($stores as $_store) {

                /* @var $_store Mage_Core_Model_Store */
                /* @var $blockList Mage_Cms_Model_Resource_Block_Collection */
                $blockList = Mage::getModel("cms/block")->getCollection();
                $blockList->addStoreFilter($_store);
                $blockList->addFieldToFilter("identifier", $blockCode);

                if ($blockList->count()==0) {
                    /* @var $newBlock Mage_Cms_Model_Block */
                    $newBlock = $blockFR;
                    $newBlock->unsetData("block_id");
                    $newBlock->setStores($_store->getId());
                    $websiteName = strtoupper(substr($_website->getCode(), strrpos($_website->getCode(), '_')+1));
                    $storeName = strtoupper(substr($_store->getCode(), strrpos($_store->getCode(), '_')+1));
                    $newBlock->setTitle("$blockName $websiteName ($storeName)");
                    $newBlock->save();
                }

            }
        }

    }

}



