<?php

/**
 * Created : 2015
 * 
 * @category Sabre
 * @package Sabre_CatalogSearch
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_CatalogSearch_Block_Product_Ajax_ScriptAutoload extends Sabre_Catalog_Block_Product_Ajax_ScriptAutoload
{

    /**
     * 
     * @param array $params
     * @return string
     */
    protected function _getAjaxUrl($params)
    {

        return $this->getUrl('catalogsearch/resultAjax/index', $params);
    }
}
