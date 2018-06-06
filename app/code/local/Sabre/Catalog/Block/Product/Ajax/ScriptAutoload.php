<?php

/**
 * Created : 2015
 * 
 * @category Sabre
 * @package Sabre_Catalog
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Catalog_Block_Product_Ajax_ScriptAutoload extends Mage_Core_Block_Template {

    protected function _beforeToHtml() {
        parent::_beforeToHtml();

        $parent = $this->getParentBlock();
        if (!($parent instanceof Mage_Catalog_Block_Product_List)) {
            Mage::throwException('Parent block must be an instance of Mage_Catalog_Block_Product_List');
        }
        
        return $this;
    }

    public function getAjaxPagesUrl($from = 1) {
        $pagesUrl = array();
        $pageLastNum = $this->getLoadedProductCollection()->getLastPageNumber();

        $requestParams = $this->getRequest()->getParams();
        $requestQuery = $this->getRequest()->getQuery();

        $requestParamsOnly = array_diff($requestParams, $requestQuery);

        for ($i = $from; $i <= $pageLastNum; $i++) {
            $urlQuery = array_merge($requestQuery, array('p' => $i));
            $urlParams = array_merge($requestParamsOnly, array('_query' => $urlQuery));
            $pagesUrl[] = $this->_getAjaxUrl($urlParams);
        }

        return $pagesUrl;
    }
    
    protected function _getAjaxUrl($params){
        
        return $this->getUrl('catalog/category/ajaxView', $params);
    }

    public function getLoadedProductCollection() {
        return $this->getParentBlock()->getLoadedProductCollection();
    }

}
