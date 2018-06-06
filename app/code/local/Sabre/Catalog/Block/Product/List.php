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
class Sabre_Catalog_Block_Product_List extends Mage_Catalog_Block_Product_List
{

    const ATTRIBUTE_IS_SET = 'a_is_set';
    const ATTRIBUTE_COLOR = 'a_filter_color';
    const ATTRIBUTE_MODEL = 'a_model';
    const ATTRIBUTE_ARTICLE = 'a_article';

    protected $_applyFilterIsSet = true;
    protected $_filterIsSetAppliedRules = false;

    public function getApplyFilterIsSet()
    {
        return $this->_applyFilterIsSet;
    }

    public function setApplyFilterIsSet($applyFilterIsSet)
    {
        $this->_applyFilterIsSet = $applyFilterIsSet;
        return $this;
    }

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection()
    {
        $this->_productCollection = parent::_getProductCollection();

        $this->_applyRulesFilterIsSet($this->_productCollection);

        $this->_redirectIfOneResult($this->_productCollection);

        return $this->_productCollection;
    }

    /**
     *
     * @param Mage_Eav_Model_Entity_Collection_Abstract $collection
     */
    protected function _applyRulesFilterIsSet($collection)
    {
        $currentCategory = Mage::registry('current_category');

        if($currentCategory){
            $articleValue = $currentCategory->getData('a_article');
            $showIsSet = $currentCategory->getData('a_show_is_set');
            $codeSet = Mage::helper('sabre_catalog')->_getCategoryAttributeSetCode($currentCategory);
            if (
                !$collection->isLoaded() &&
                !$this->_filterIsSetAppliedRules &&
                $this->_applyDefaultFilter()
            ) {
                $modelValue = $currentCategory->getData('a_model');
                $filterColorValue = $currentCategory->getData('a_filter_color');
                $collection->addAttributeToFilter(Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_IS_SET, $showIsSet);
                if (!(is_null($modelValue))) {
                    $collection->addAttributeToFilter('a_model_' . $codeSet, $modelValue);
                }
                if (!(is_null($articleValue))) {
                    $collection->addAttributeToFilter('a_article_' . $codeSet, $articleValue);
                }

                if (!(is_null($filterColorValue))) {
                    $collection->addAttributeToFilter('a_filter_color', $filterColorValue);
                }

            } elseif (is_null($this->getRequest()->getParam('a_article_' . $codeSet, null))) {
                $collection->addAttributeToFilter(Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_IS_SET, $showIsSet);
                if (!(is_null($articleValue))) {
                    $collection->addAttributeToFilter('a_article_' . $codeSet, $articleValue);
                    $this->_filterIsSetAppliedRules = true;
                }
            }
            $this->_filterIsSetAppliedRules = true;
        }


    }

    /**
     *
     * @return boolean
     */
    protected function _applyDefaultFilter()
    {
        $_filters = $this->getLayer()->getFilterableAttributes();
        $_filtersValues = array();

        /* @var $_filter Mage_Catalog_Model_Resource_Eav_Attribute */
        foreach ($_filters as $_filter) {
            if (($_val = $this->getRequest()->getParam($_filter->getAttributeCode(), null))) {
                $_filtersValues[$_filter->getAttributeCode()] = $_val;
            }
        }

        /**
         * CAS 1 : PAS DE FILTRE
         * Si aucun filtre n'est positionné sur la liste, on filtre automatiquement sur "Est un set" à oui pour ne voir que les sets.
         * Dès qu'un filtre est appliqué sur l'article, le filtre automatique sur "Est un set" n'est plus actif.
         */
        if (empty($_filtersValues)) {
            return true;
        } /**
         * CAS 2 : Filtre Model ou Couleur Seulement
         * Si filtrage sur modèle uniquement ou couleur uniquement, conserver le filtrage automatique sur "Est un set".
         */ elseif (
            count($_filtersValues) == 1 &&
            array_intersect(
                array_keys($_filtersValues), array(
                    Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_MODEL,
                    Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_FILTER_COLOR
                )
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param Mage_Eav_Model_Entity_Collection_Abstract $collection
     */
    protected function _redirectIfOneResult($collection)
    {
        if ($collection->isLoaded() && $collection->getSize() == 1) {
            /* @var $product Mage_Catalog_Model_Product */
            $product = $collection->getFirstItem();

            //Redirect to product page
            Mage::app()
                ->getFrontController()
                ->getResponse()
                ->setRedirect($product->getProductUrl())
                ->sendResponse();
        }
    }

    /**
     * Build product name from three parameters a_model, color, a_article
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    public function getProductName($product)
    {
        return Mage::helper('sabre_catalog')->getCustomProductName($product);
    }
}
