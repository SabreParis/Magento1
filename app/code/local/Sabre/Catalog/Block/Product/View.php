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
class Sabre_Catalog_Block_Product_View extends Mage_Catalog_Block_Product_View
{

    protected $_productsColorVariantBlockName = 'products_color_variant';
    protected $_productsArticleVariantBlockName = 'products_article_variant';
    protected $_productsColorVariantBlock;
    protected $_productsArticleVariantBlock;

    /**
     * 
     * @return Sabre_Catalog_Block_Product_View_ProductsVariant
     */
    public function getProductsColorVariantBlockHtml()
    {
        if ($this->getChild($this->_productsColorVariantBlockName)) {
            return $this->getChildHtml($this->_productsColorVariantBlockName);
        }

        return $this->getProductsColorVariantBlock()->toHtml();
    }

    /**
     * 
     * @return Sabre_Catalog_Block_Product_View_ProductsVariant
     */
    public function getProductsArticleVariantBlockHtml()
    {
        if ($this->getChild($this->_productsArticleVariantBlockName)) {
            return $this->getChildHtml($this->_productsArticleVariantBlockName);
        }

        return $this->getProductsArticleVariantBlock()->toHtml();
    }

    /**
     * 
     * @return Sabre_Catalog_Block_Product_View_ProductsVariant
     */
    public function getProductsColorVariantBlock()
    {
        if (!$this->_productsColorVariantBlock) {
            $this->setProductsColorVariantBlock();
        }

        return $this->_productsColorVariantBlock;
    }

    /**
     * 
     * @return Sabre_Catalog_Block_Product_View_ProductsVariant
     */
    public function getProductsArticleVariantBlock()
    {
        if (!$this->_productsArticleVariantBlock) {
            $this->setProductsArticleVariantBlock();
        }

        return $this->_productsArticleVariantBlock;
    }

    /**
     * 
     * @param string $blockName
     * @return \Sabre_Catalog_Block_Product_View
     */
    public function setProductsColorVariantBlock($blockName = null)
    {
        $this->_productsColorVariantBlock = $this->_addProductsVariantBlock($blockName);

        return $this;
    }

    /**
     * 
     * @param string $blockName
     * @return \Sabre_Catalog_Block_Product_View
     */
    public function setProductsArticleVariantBlock($blockName = null)
    {
        $this->_productsArticleVariantBlock = $this->_addProductsVariantBlock($blockName);

        return $this;
    }

    /**
     * 
     * @param string $blockName
     * @return Sabre_Catalog_Block_Product_View_ProductsVariant
     */
    protected function _addProductsVariantBlock($blockName = null)
    {
        $blockInst = null;
        if ($blockName) {
            if (($_block = $this->getLayout()->getBlock($blockName))) {
                $blockInst = $_block;
            }
        }

        if (!$blockInst) {
            if (!$blockName) {
                $blockName = microtime();
            }
            $blockInst = $this
                ->getLayout()
                ->createBlock('sabre_catalog/product_view_productsVariant', $blockName)
            ;
        }

        $blockInst->setData('product', $this->getProduct());

        return $blockInst;
    }

    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        $this->setChild($this->_productsArticleVariantBlockName, $this->getProductsArticleVariantBlock());
        $this->setChild($this->_productsColorVariantBlockName, $this->getProductsColorVariantBlock());

        return $this;
    }
}
