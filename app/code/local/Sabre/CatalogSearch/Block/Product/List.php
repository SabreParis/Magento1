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
class Sabre_CatalogSearch_Block_Product_List extends Sabre_Catalog_Block_Product_List
{
    /**
     * Do not apply filter "is set" in case of search
     * 
     * @var boolean 
     */
    protected $_applyFilterIsSet = false;
    
    /**
     * Get layer object
     *
     * @return Mage_CatalogSearch_Model_Layer
     */
    public function getLayer()
    {
        return Mage::getSingleton('catalogsearch/layer');
    }
}
