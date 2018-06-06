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
class Sabre_Catalog_Model_Resource_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return \Sabre_Catalog_Model_Resource_Product_Collection
     */
    public function addProductsColorVariantToFilter($product)
    {

        $modelAttributeCode = Mage::helper('sabre_catalog')->getProductAModelAttributeCode($product);
        $articleAttributeCode = Mage::helper('sabre_catalog')->getProductAArticleAttributeCode($product);

        $this->_addProductsVariantToFilter(
            Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_COLOR, 
            $articleAttributeCode,
            $product->getData($articleAttributeCode),
            $modelAttributeCode,
            $product->getData($modelAttributeCode)
        );
        
        return $this;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return \Sabre_Catalog_Model_Resource_Product_Collection
     */
    public function addProductsArticleVariantToFilter($product)
    {
        $modelAttributeCode = Mage::helper('sabre_catalog')->getProductAModelAttributeCode($product);
        $articleAttributeCode = Mage::helper('sabre_catalog')->getProductAArticleAttributeCode($product);

        $this->_addProductsVariantToFilter(
            $articleAttributeCode,
            Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_COLOR, 
            $product->getData(Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_COLOR),
            $modelAttributeCode,
            $product->getData($modelAttributeCode)
        );
        
        return $this;
    }

    /**
     * 
     * @param string $variantAttrCode
     * @param string $fixedAttrCode1
     * @param string $fixedAttrValue1
     * @param string $fixedAttrCode2
     * @param string $fixedAttrValue2
     * @return \Sabre_Catalog_Model_Resource_Product_Collection
     */
    protected function _addProductsVariantToFilter($variantAttrCode, $fixedAttrCode1, $fixedAttrValue1, $fixedAttrCode2, $fixedAttrValue2)
    {
        $productAttributes = array_unique(
            array_merge(
                [$variantAttrCode, $fixedAttrCode1, $fixedAttrCode1],
                Mage::getSingleton('catalog/config')->getProductAttributes())
        );
        
        $this
            ->addAttributeToSelect($productAttributes)
            ->addAttributeToFilter($fixedAttrCode1, ['eq'=>$fixedAttrValue1])
            ->addAttributeToFilter($fixedAttrCode2, ['eq'=>$fixedAttrValue2])
            ->addPriceData()
            ->addUrlRewrite()
            ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
        
        $this
            ->joinAttribute($variantAttrCode, 
                "catalog_product/$variantAttrCode", 
                'entity_id');
        
        $this
            ->getSelect()
            ->join(
                array('at_opt_variant' => $this->getTable('eav/attribute_option')),
                "at_opt_variant.option_id = {$this->_getAttributeTableAlias($variantAttrCode)}.value",
                array('at_opt_variant_sort_order'=>'at_opt_variant.sort_order')
            )
            ->join(
                array('at_opt_value_variant' => $this->getTable('eav/attribute_option_value')),
                "at_opt_value_variant.option_id = at_opt_variant.option_id"
                . " AND at_opt_value_variant.store_id = 0",
                array($this->_getAttributeDefautOptionValueAlias($variantAttrCode) => 'at_opt_value_variant.value')
            )
            ->order("at_opt_variant.sort_order ASC");
        
        return $this;
    }
    
    /**
     * 
     * @param string $attributeCode
     * @return string
     */
    protected function _getAttributeDefautOptionValueAlias($attributeCode){
        
        return $attributeCode . '_code';
    }
    
}
