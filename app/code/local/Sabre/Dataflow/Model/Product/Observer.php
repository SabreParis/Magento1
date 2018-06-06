<?php

class Sabre_Dataflow_Model_Product_Observer
{

    private $__localesByStore = null;
    private $__allOptions = array();

    public function initInventory($observer) {

        $event = $observer->getEvent();
        $currentProductXml  = $event->getProductXml();
        /* @var $currentProduct Mage_Catalog_Model_Product */
        $currentProduct     = $event->getProduct();
        /* @var $productResource Mage_Catalog_Model_Resource_Product */
        $productResource    = $event->getProductResource();

        // Récupération du produit.
        $productId = Mage::getModel("catalog/product")->getIdBySku($currentProduct->getSku());
        // Initialisation du stock
        $stockItem = Mage::getModel("cataloginventory/stock_item");
        if (!$productId) {
            // Pas de produit, donc pas de stock. Il faut créer l'enregistrement
            $stockItem->setStockId(1);
            $stockItem->setUseConfigManageStock(1);
            $stockItem->setUseConfigMinSaleQty(1);
            $stockItem->setUseConfigMaxSaleQty(1);
            $stockItem->setUseConfigEnableQtyIncrements(1);
        } else {
            // Un produit, On vérifie qu'il y ait un enregistrement de stock
            $stockItem->loadByProduct($productId);
            if (!$stockItem->getId()) {
                $stockItem->setProductId($productId);
                $stockItem->setStockId(1);
                $stockItem->setUseConfigManageStock(1);
                $stockItem->setUseConfigMinSaleQty(1);
                $stockItem->setUseConfigMaxSaleQty(1);
                $stockItem->setUseConfigEnableQtyIncrements(1);
            } else {
                $stockItem->setUseConfigMinSaleQty(1);
                $stockItem->setUseConfigMaxSaleQty(1);
                $stockItem->setUseConfigEnableQtyIncrements(1);
            }
        }

        $currentProduct->setStockData(true);
        $currentProduct->setStockItem($stockItem);

    }

    /**
     * observer : ayaline_dataflow_manager_import_catalog_product_before_save
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function initCategories($observer) {
        $event = $observer->getEvent();
        $currentProductXml  = $event->getProductXml();
        /* @var $currentProduct Mage_Catalog_Model_Product */
        $currentProduct     = $event->getProduct();
        if (isset($currentProductXml->categories)) {
            $categories = $currentProductXml->categories;
            $categoryIds = array();
            foreach ($categories->children() as $_category) {
                // $merchantCode = (string)$_category["merchant_code"][0];
                $magentoId = (string)$_category["destination_id"][0];
                $categoryIds[] = $magentoId;
            }
            $currentProduct->setCategoryIds($categoryIds);
        }
    }

    /**
     * observer : ayaline_dataflow_manager_import_catalog_product_after_save
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function updatePrices($observer) {

        // Mise à jour des tarifs en fonction des stores
        $event = $observer->getEvent();
        $currentProductXml  = $event->getProductXml();
        /* @var $currentProduct Mage_Catalog_Model_Product */
        $currentProduct     = $event->getProduct();

        foreach ($currentProductXml->prices->children() as $_websitePrice) {
            $price = (string)$_websitePrice;
            $websiteCode = (string)$_websitePrice['website'];
            $website = Mage::getModel('core/website')->load($websiteCode, 'code');
            $currentProduct->addAttributeUpdate('price', $price, $website->getDefaultStore()->getId());
        }

    }

    /**
     * observer : ayaline_dataflow_manager_import_catalog_product_before_save
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function translateOptions($observer) {
        $event = $observer->getEvent();
        $currentProductXml  = $event->getProductXml();
        /* @var Ayaline_DataflowManager_Model_Resource_Catalog_Attribute $attribute_resource */
        $attribute_resource = $event->getAttributeResource();

        // Récupération des websites / stores by language
        if (!$this->__localesByStore) {
            $localesByStore = array();
            $websites = $currentProductXml->websites;
            foreach ($websites->children() as $_website) {
                $websiteIdentifier = $_website->getAttribute('identifier');
                $website = Mage::getModel("core/website")->load($websiteIdentifier, "code");
                foreach ($_website->children() as $tag => $content) {
                    if ($tag=="language") {
                        $_locale = $content->getAttribute("idref");
                        $_store = Mage::helper('ayaline_dataflowmanager/catalog')->getStoreByWebsiteAndLocale($website->getId(), $_locale);
                        $localesByStore[$_locale][] = $_store->getId();
                    }
                }
            }
            $this->__localesByStore = $localesByStore;
        }

        if (isset($currentProductXml->option_translations)) {
            $options2translate = $currentProductXml->option_translations;
            foreach ($options2translate->children() as $_option2translate) {
                $attribs = $_option2translate->attributes();
                $attributeCode = (string)$attribs['code'];
                $optionDefault = (string)$attribs['default'];
                // get attribute
                $attribute = Mage::getModel("eav/entity_attribute")->loadByCode(Mage_Catalog_Model_Product::ENTITY, $attributeCode);
                if ($attribute && $attribute->getId()) {
                    foreach ($_option2translate->children() as $tag => $translation) {
                        if ($tag == "translation") {
                            $_translationLocale = (string)$translation->getAttribute("locale");
                            $_translateLabel = (string)$translation;
                            foreach ($this->__localesByStore as $locale => $stores) {
                                foreach ($stores as $storeId) {
                                    if ($_translationLocale == $locale) {
                                        $testCode = "{$attribute->getId()}--$storeId--$locale--$optionDefault";
                                        if (!in_array($testCode, $this->__allOptions)) {
                                            $attribute_resource->insertOrUpdateStoreLabels($attribute, $storeId, $optionDefault, $_translateLabel);
                                            array_push($this->__allOptions, $testCode);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

};