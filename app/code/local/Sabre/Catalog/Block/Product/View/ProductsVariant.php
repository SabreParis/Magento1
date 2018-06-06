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

/**
 * @method Mage_Catalog_Model_Product getProduct()
 * @method $this setProduct(Mage_Catalog_Model_Product $product)
 */
class Sabre_Catalog_Block_Product_View_ProductsVariant extends Mage_Core_Block_Template
{

    protected $_productsColorVariant;
    protected $_productsArticleVariant;

    /**
     * 
     * @return null|Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProductsColorVariant()
    {
        if ($this->_productsColorVariant) {

            return $this->_productsColorVariant;
        }

        $product = $this->getProduct();
        if (!$product || !$product->getId()) {

            return null;
        }

        /** @var Sabre_Catalog_Model_Resource_Product_Collection */
        $this->_productsColorVariant = Mage::getResourceModel('sabre_catalog/product_collection')
            ->addProductsColorVariantToFilter($product);

        return $this->_productsColorVariant;
    }

    /**
     * @return null|Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProductsArticleVariant()
    {
        if ($this->_productsArticleVariant) {

            return $this->_productsArticleVariant;
        }

        $product = $this->getProduct();
        if (!$product || !$product->getId()) {

            return null;
        }

        /** @var Sabre_Catalog_Model_Resource_Product_Collection */
        $this->_productsArticleVariant = Mage::getResourceModel('sabre_catalog/product_collection')
            ->addProductsArticleVariantToFilter($product);

        return $this->_productsArticleVariant;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    public function getAttributeColorImgUrl($product)
    {
        return Mage::helper('sabre_catalog')->getProductAttributeImgUrl(Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_COLOR, $product->getData('color_code'));
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    public function getAttributeArticleImgUrl($product)
    {
        $attrCode = Mage::helper('sabre_catalog')->getProductAArticleAttributeCode($product);
        $attrValue = Mage::helper('sabre_catalog')->getAttributeOptionDefaultValue($product, $attrCode);

        return Mage::helper('sabre_catalog')->getProductAttributeImgUrl($attrCode, $attrValue);
    }

}
