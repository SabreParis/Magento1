<?php

/** @var $this Mage_Core_Model_Resource_Setup */

// ****************************
// Création des stores
// ****************************

// Liste des codes de websites
$websiteFranceCode = "sabre_fr";
$websiteAllemagneCode = "sabre_de";
$websiteHollandeCode = "sabre_nl";
$websiteAngleterreCode = "sabre_uk";
$websiteBelgiqueCode = "sabre_be";
$websiteItalieCode = "sabre_it";
$websiteUsaCode = "sabre_com";

$websiteCodes = array(
    $websiteAllemagneCode => array('Deutsch (DE)', 'English (DE)'),
    $websiteHollandeCode => array('Dutch (NL)', 'English (NL)'),
    $websiteAngleterreCode => array('English (UK)', 'Français (UK)'),
    $websiteBelgiqueCode => array('Dutch (BE)', 'Français (BE)'),
    $websiteItalieCode => array('Italiano (IT)', 'English (IT)'),
    $websiteUsaCode => array('English (US)', 'Français (US)'),
);

// Récupération du website France
$websiteFrance = Mage::getModel("core/website")->load($websiteFranceCode, "code");

// Création des websites
$i=1;
foreach ($websiteCodes as $_websiteCode => $_storeCodes) {
    $i+=1;
    $newWebsite = Mage::getModel("core/website")->load($_websiteCode, "code");
    if (!$newWebsite->getId()) {
        // Création des websites
        $newWebsite->setCode($_websiteCode);
        $newWebsite->setName("sabre-paris." . substr($_websiteCode, strpos($_websiteCode, '_') + 1));
        $newWebsite->setDefaultGroupId($websiteFrance->getDefaultGroupId());
        $newWebsite->setIsDefault(0);
        $newWebsite->setSortOrder($i * 10);
        $newWebsite->save();
        // Création du store group
        $newStoreGroup = Mage::getModel("core/store_group");
        $newStoreGroup->setWebsiteId($newWebsite->getId());
        $newStoreGroup->setName($websiteFrance->getDefaultGroup()->getName());
        $newStoreGroup->setRootCategoryId($websiteFrance->getDefaultGroup()->getRootCategoryId());
        $newStoreGroup->save();
        // Affectation du group au website
        $newWebsite->setDefaultGroupId($newStoreGroup->getId());
        $newWebsite->save();
        // Création des stores
        foreach ($_storeCodes as $j => $_storeCode) {
            $normalizedStoreCode = strtolower(str_replace('ç', 'c', substr($_storeCode, 0, strpos($_storeCode, ' '))));
            $newStore = Mage::getModel("core/store");
            $newStore->setCode($_websiteCode . '_' . $normalizedStoreCode);
            $newStore->setWebsiteId($newWebsite->getId());
            $newStore->setGroupId($newStoreGroup->getId());
            $newStore->setName($_storeCode);
            $newStore->setSortOrder($i * 10 + $j + 1);
            $newStore->setIsActive(1);
            $newStore->save();
            if ($j == 0) {
                $newStoreGroup->setDefaultStoreId($newStore->getId());
                $newStoreGroup->save();
            }
        }
    }
}
