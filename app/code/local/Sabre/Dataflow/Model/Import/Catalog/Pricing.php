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
class Sabre_Dataflow_Model_Import_Catalog_Pricing extends Ayaline_DataflowManager_Model_Import_Abstract
{

    /**
     * {@inheritdoc}
     */
    protected function _getDocumentation()
    {
        return <<<DOC
Import des tarifs publics
DOC;
    }

    /**
     * {@inheritdoc}
     */
    protected function _import($filename)
    {
        // clé : code Sabre | valeur : store Model (defaut store of mapped website)
        $stores = $this->__getStoreMapping();

        /* @var $xmlIterator SimpleXMLIterator */
        $xmlIterator = simplexml_load_file($filename, "SimpleXMLIterator");
        $cpt = 0;
        for ($xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next()) {
            $this->_startProfiling("process_product");
            $cpt++;
            try {

                /* @var $xml SimpleXMLIterator */
                $xml = $xmlIterator->current();
                $xmlAttributes = $xml->attributes();
                $sku = isset($xmlAttributes->sku) ? (String)$xmlAttributes->sku : null;
                // Récupération du produit.
                $productId = Mage::getModel("catalog/product")->getIdBySku($sku);
                $this->_log("Traitement du produit numero $cpt - $sku");
                if (!$productId) {
                    throw new Mage_Exception("\t==> Le SKU $sku n'existe pas dans la base Magento.");
                }

                $product = Mage::getModel("catalog/product");
                $product->setId($productId);

                $first = true;
                foreach ($xml->children() as $priceItem) {
                    $_price = (String)$priceItem;
                    $_websiteCode = (String)$priceItem["web"];
                    $_price = str_replace(",", ".", $_price);
                    $_price = number_format($_price, 4, ".", "");

                    if ($first) {
                        // on affecte le premier tarif comme valeur patr défaut. Difficile de faire mieux...
                        $this->_log("\t Affectation du tarif $_price par défaut");
                        $product->addAttributeUpdate('price', $_price, 0);
                    }
                    $first = false;

                    $this->_log("\t Affectation du tarif $_price pour le store $_websiteCode");
                    $product->addAttributeUpdate('price', $_price, $stores[$_websiteCode]);
                }

                $this->_log("\t==> produit traité.");

            } catch (Mage_Exception $e) {
                $this->_log($e->getMessage(), Zend_Log::ERR);
            } catch (Exception $e) {
                $this->_log($e->getMessage(), Zend_Log::ERR);
                $this->_logException($e);
            }

            $this->_stopProfiling("process_product");

        }

    }

    /**
     * {@inheritdoc}
     */
    protected function _validate()
    {
        return true;
    }

    /**
     * @return array
     */
    private function __getStoreMapping()
    {
        // Récupération de tous les wesbites
        $websiteCollection = Mage::getModel('core/website')->getCollection();
        $_websites = array();
        foreach ($websiteCollection as $_website) {
            /* @var $_website Mage_Core_Model_Website */
            $_websites[$_website->getCode()] = $_website->getDefaultStore()->getId();
        }

        // Récupération du mapping website SABRE / MAGENTO
        $websitesMapping = Mage::getStoreConfig("ayaline_dataflowmanager/import_product/config_mapping_website");
        $websitesMapping = unserialize($websitesMapping);
        $_mapping = array();
        foreach ($websitesMapping as $_websiteMapping) {
            $_mapping[$_websiteMapping['sabre_website_code']] = $_websiteMapping['magento_website_code'];
        }

        // Création du tableau de mapping final
        $mapping = array();
        foreach ($_mapping as $_sabreWebsiteCode => $_mageWebsiteCode) {
            $mapping[$_sabreWebsiteCode] = $_websites[$_mageWebsiteCode];
        }

        return $mapping;
    }
}
