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
class Sabre_Dataflow_Model_Import_Catalog_Inventory extends Ayaline_DataflowManager_Model_Import_Abstract
{

    private $__nostockProductIds = array();
    private $__processedProductIds = array();

    /**
     * {@inheritdoc}
     */
    protected function _getDocumentation()
    {
        return <<<DOC
Import des produits en rupture
DOC;
    }

    protected function _beforeImport($filename)
    {

        $this->_startProfiling("pretraitement");

        $this->_log("=================== PRETRAITEMENT ===================");
        parent::_beforeImport($filename);
        $this->_log("Récupération des produits en rupture");

        // Liste de tous les produits en rupture
        // Un produit en rupture est un produit :
        // - géré en stock
        // - avec une disponibilité à épuisé

        /* @var $inventories Mage_CatalogInventory_Model_Resource_Stock_Item_Collection */
        $inventories = Mage::getModel("cataloginventory/stock_item")->getCollection();
        $inventories->addFieldToFilter("manage_stock", 1);
        $inventories->addFieldToFilter("is_in_stock", 0);

        $this->__nostockProductIds = $inventories->getColumnValues("product_id");

        $this->_stopProfiling("pretraitement");

    }

    protected function _afterImport($filename)
    {

        $this->_startProfiling("posttraitement");

        $this->_log("=================== POSTTRAITEMENT ===================");
        parent::_afterImport($filename);
        $this->_log("Récupération des produits en rupture");
        $nowAvailableProducts = array_diff($this->__nostockProductIds, $this->__processedProductIds);

        $this->_log("Les produits suivants sont à nouveau disponibles :");
        foreach ($nowAvailableProducts as $nowAvailableProduct) {
            try {
                $this->_startProfiling("availableagain");
                $stockItem = Mage::getModel("cataloginventory/stock_item")->loadByProduct($nowAvailableProduct);
                $stockItem->setUseConfigManageStock(1);
                $stockItem->setManageStock(0);
                $stockItem->setIsInStock(1);
                $stockItem->setUseConfigMinSaleQty(1);
                $stockItem->setUseConfigMaxSaleQty(1);
                $stockItem->setUseConfigEnableQtyIncrements(1);
                $stockItem->save();
                $this->_log("\t => id : $nowAvailableProduct");
                $this->_stopProfiling("availableagain");
            } catch(Exception $e) {
                $this->_log($e->getMessage(), Zend_Log::ERR);
            }
        }

        $this->_stopProfiling("posttraitement");
    }

    /**
     * {@inheritdoc}
     */
    protected function _import($filename)
    {

        $this->_log("=================== TRAITEMENT ===================");
        $handle = @fopen($filename, "r");
        if ($handle) {
            while (($sku = fgets($handle, 4096)) !== false) {

                $this->_startProfiling("process_product");

                try {

                    $sku = trim($sku);

                    $this->_log("Traitement du produit $sku");

                    // Récupération du produit.
                    $productId = Mage::getModel("catalog/product")->getIdBySku($sku);
                    if (!$productId) {
                        throw new Mage_Exception("\t==> Le SKU $sku n'existe pas dans la base Magento.");
                    }

//                    $product = Mage::getModel("catalog/product");
//                    $product->setId($productId);

                    $stockItem = Mage::getModel("cataloginventory/stock_item")->loadByProduct($productId);
                    if (!$stockItem->getId()) {
                        // Création du stock item
                        $stockItem->setProductId($productId);
                        $stockItem->setStockId(1);
                        $stockItem->setUseConfigManageStock(0);
                        $stockItem->setManageStock(1);
                        $stockItem->setIsInStock(0);
                        $stockItem->setUseConfigMinSaleQty(1);
                        $stockItem->setUseConfigMaxSaleQty(1);
                        $stockItem->setUseConfigEnableQtyIncrements(1);
                    } else {
                        // modification du stock item
                        $stockItem->setUseConfigManageStock(0);
                        $stockItem->setManageStock(1);
                        $stockItem->setIsInStock(0);
                    }

                    $stockItem->save();

                    $this->__processedProductIds[] = $productId;

                } catch (Mage_Exception $e) {
                    $this->_log($e->getMessage(), Zend_Log::ERR);
                } catch (Exception $e) {
                    $this->_log($e->getMessage(), Zend_Log::ERR);
                    $this->_logException($e);
                }

                $this->_stopProfiling("process_product");


            }
            if (!feof($handle)) {
                $this->_log("Erreur: fgets() a échoué", Zend_Log::ERR);
            }
            fclose($handle);
        } else {
            throw new Mage_Exception("File can not be read");
        }

    }

    /**
     * {@inheritdoc}
     */
    protected function _validate()
    {
        return true;
    }
}
