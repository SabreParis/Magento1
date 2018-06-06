<?php

/**
 * Created : 2015
 * 
 * @category Ayaline
 * @package Ayaline_XXXX
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Dataflow_Model_Import_Catalog_Shops extends Ayaline_DataflowManager_Model_Import_Abstract
{

    /**
     * {@inheritdoc}
     */
    protected function _getDocumentation()
    {
        return <<<DOC
Import des points de vente
DOC;
    }

    /**
     * {@inheritdoc}
     */
    protected function _import($filename)
    {

        $importedShopIds = array();

        /* @var $xmlIterator SimpleXMLIterator */
        $xmlIterator = simplexml_load_file($filename, "SimpleXMLIterator");
        $cpt = 0;
        for ($xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next()) {
            $this->_startProfiling("process_shop");
            $cpt++;
            try {

                /* @var $xml SimpleXMLIterator */
                $xml = $xmlIterator->current();
                $xmlAttributes = $xml->attributes();

                $name = isset($xmlAttributes->RSN) ? (String)$xmlAttributes->RSN : null;
                $address1 = isset($xmlAttributes->ADR1) ? (String)$xmlAttributes->ADR1 : null;
                $address2 = isset($xmlAttributes->ADR2) ? (String)$xmlAttributes->ADR2 : null;
                $address3 = isset($xmlAttributes->ADR3) ? (String)$xmlAttributes->ADR3 : null;
                $zipcode = isset($xmlAttributes->CP) ? (String)$xmlAttributes->CP : null;
                $sabreId = isset($xmlAttributes->ID) ? (String)$xmlAttributes->ID : null;
                $mail = isset($xmlAttributes->MAIL) ? (String)$xmlAttributes->MAIL : null;
                $countryISO2 = isset($xmlAttributes->PAY) ? (String)$xmlAttributes->PAY : null;
                $phone = isset($xmlAttributes->TEL) ? (String)$xmlAttributes->TEL : null;
                $shopGroup = isset($xmlAttributes->TYPE) ? (String)$xmlAttributes->TYPE : null;
                $city = isset($xmlAttributes->VIL) ? (String)$xmlAttributes->VIL : null;
                $gps = isset($xml->GPS) ? $xml->GPS : null;
                $latitude = null;
                $longitude = null;
                if ($gps) {
                    $gpsAttributes = $gps->attributes();
                    $latitude = isset($gpsAttributes->LA) ? (String)$gpsAttributes->LA : null;
                    $longitude = isset($gpsAttributes->LO) ? (String)$gpsAttributes->LO : null;
                }

                $this->_log("Traitement du magasin $name / $sabreId");

                // Récupération du magasin en base de données
                /* @var $shops Ayaline_Shop_Model_Mysql4_Shop_Collection */
                /* @var $shop Ayaline_Shop_Model_Shop */
                $shops = Mage::getModel("ayalineshop/shop")->getCollection();
                $shop = $shops->addFieldToFilter("sabre_code", $sabreId)->getFirstItem();

                if (!$shop->getId()) {
                    $shop->setIsActive(true);
                }

                // Si pas de coordonnées => on désactive le shop.
                if (!($latitude && $longitude)) {
                    $shop->setIsActive(false);
                }

                $shop->setData("stores", array(0));
                $shop->setData("title", $name);
                $shop->setData("street1", $address1);
                $shop->setData("street2", implode(" - ", array_filter(array($address2, $address3))));
                $shop->setData("postcode", $zipcode);
                $shop->setData("city", $city);
                $shop->setData("country_id", $countryISO2);
                $shop->setData("telephone", $phone);
                $shop->setData("email", $mail);
                $shop->setData("sabre_code", $sabreId);
                $shop->setData("latitude", $latitude);
                $shop->setData("longitude", $longitude);

                $groupId = null;
                $usedForShipping = false;
                if ($shopGroup=='boutique') {
                    $groupId = Mage::getStoreConfig("ayaline_dataflowmanager/import_shops/group_pdv");
                    $usedForShipping = true;
                } else {
                    $groupId = Mage::getStoreConfig("ayaline_dataflowmanager/import_shops/group_revendeur");
                }
                $shop->setData("group_id", $groupId);
                $shop->setData("used_for_shipping", $usedForShipping);
                $shop->save();

                $importedShopIds[] = $shop->getId();

                $this->_log("\t==> magasin traité.");

            } catch (Mage_Exception $e) {
                $this->_log($e->getMessage(), Zend_Log::ERR);
            } catch (Exception $e) {
                $this->_log($e->getMessage(), Zend_Log::ERR);
                $this->_logException($e);
            }

            $this->_stopProfiling("process_shop");

        }

        $this->_startProfiling("process_delete_shop");
        /* @var $shops Ayaline_Shop_Model_Mysql4_Shop_Collection */
        $shops = Mage::getModel("ayalineshop/shop")->getCollection();
        $allAshopIds = $shops->getAllIds();
        $shops2delete = array_diff($allAshopIds, $importedShopIds);
        if ($shops2delete && count($shops2delete)>0) {
            foreach ($shops2delete as $shops2deleteId) {
                $shop = Mage::getModel("ayalineshop/shop")->setId($shops2deleteId);
                $shop->delete();
            }
        }
        $this->_stopProfiling("process_delete_shop");

    }

    /**
     * {@inheritdoc}
     */
    protected function _validate()
    {
        return true;
    }
}
