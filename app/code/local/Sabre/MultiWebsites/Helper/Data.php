<?php
class Sabre_MultiWebsites_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getOtherActiveWebsites() {

        // Liste des websites actifs
        $websites = Mage::getModel('core/website')->getCollection();
        $activeWebsites = array();

        /* @var $currentWebsite Mage_Core_Model_Website */
        $currentWebsite = Mage::app()->getWebsite();

        foreach ($websites as $website) {
            /* @var $website Mage_Core_Model_Website */
            if ($currentWebsite->getId()==$website->getId()) {
                // On ne renvoie pas le website courant.
                continue;
            }
            $stores = $website->getStores();
            $displayWebsite = false;
            foreach ($stores as $store) {
                /* @var $store Mage_Core_Model_Store */
                // echo active stores !
                if ($store->getIsActive()) {
                    $displayWebsite = true;
                }
            }
            if ($displayWebsite) {
                $activeWebsites[] = $website;
            }
        }

        return $activeWebsites;

    }

    public function getOtherActiveStores($onlyDefaultStores = false) {

        // Liste des websites actifs
        $websites = Mage::getModel('core/website')->getCollection();
        $activeStores = array();

        /* @var $currentWebsite Mage_Core_Model_Store */
        $currentStore = Mage::app()->getStore();

        foreach ($websites as $website) {
            /* @var $website Mage_Core_Model_Website */
            $stores = $website->getStores();
            foreach ($stores as $store) {
                if ($store->getIsActive()
                    && $store->getId()!=$currentStore->getId()
                    && ($onlyDefaultStores ? $website->getDefaultStore()->getId()==$store->getId() : true)) {
                    $activeStores["{$store->getId()}"] = $store;
                }
            }
        }

        return $activeStores;

    }

}
