<?php

class Sabre_Catalog_Block_Product_Widget_SkuFilter extends Mage_Catalog_Block_Product_Abstract
    implements Mage_Widget_Block_Interface
{
    /**
     * Initialize block's cache and template settings.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->addPriceBlockType('bundle', 'bundle/catalog_product_price', 'bundle/catalog/product/price.phtml');
    }
    /**
     * Retrieve sku values for products.
     *
     * @return string
     */
    public function getProductsSku()
    {
        if (!$this->hasData('products_sku')) {
            return;
        }

        return $this->getData('products_sku');
    }

    protected function _getProductsSkuList()
    {
        $productsSku = $this->getProductsSku();
        if ($this->getProductsSku()) {
            return explode(',', $productsSku);
        }

        return;
    }

    /**
     * Prepare collection for recent product list.
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection|object|Varien_Data_Collection
     */
    public function getProductsBySkuCollection()
    {
        $productsSkuList = $this->_getProductsSkuList();

        if ($productsSkuList) {
            /** @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = Mage::getResourceModel('catalog/product_collection');
            $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
            $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToFilter('sku', array('in' => $productsSkuList));
            $collection->getSelect()->order(new Zend_Db_Expr('FIELD(sku, "'.implode('","', $productsSkuList).'") ASC'));

            return $collection;
        }

        return;
    }
}
