<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 23/12/2016
 * Time: 12:24
 */
abstract class Sabre_MultiWebsites_Block_Seo_AlternateLang_Abstract extends Mage_Core_Block_Template
{

    abstract protected function updateUrlRewriteCollection(Mage_Core_Model_Resource_Url_Rewrite_Collection $urlRewrites);

    abstract protected function preCheck();

    public function getAlternateUrls() {

        if (!$this->preCheck()) {
            return array();
        }

        // Récupération des stores actifs
        $stores = $this->helper('sabre_multiwebsites')->getOtherActiveStores(true);

        // lister toutes les réécritures d'URL de l'item...
        /* @var $urlRewrites Mage_Core_Model_Resource_Url_Rewrite_Collection */
        $urlRewrites = Mage::getModel("core/url_rewrite")->getCollection();
        $urlRewrites->addStoreFilter(array_keys($stores));
        $urlRewrites->addFieldToFilter("is_system", 1);
        $this->updateUrlRewriteCollection($urlRewrites);

        $alternateUrls = array();
        foreach ($urlRewrites as $urlRewrite) {
            /* @var $urlRewrite Mage_Core_Model_Url_Rewrite */
            if (!key_exists($urlRewrite->getStoreId(), $alternateUrls)) {
                $alternateUrls["{$urlRewrite->getStoreId()}"] = array(
                    "base_url" => Mage::app()->getStore($urlRewrite->getStoreId())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB),
                    "uri" => $urlRewrite->getRequestPath(),
                    "lang" => substr(Mage::getStoreConfig("general/locale/code", $urlRewrite->getStoreId()), 0, 2),
                    "country" => Mage::getStoreConfig("general/country/default", $urlRewrite->getStoreId())
                );
            }
        }

        return $alternateUrls;
    }


}