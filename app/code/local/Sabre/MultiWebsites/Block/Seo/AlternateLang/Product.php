<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 23/12/2016
 * Time: 12:24
 */
class Sabre_MultiWebsites_Block_Seo_AlternateLang_Product extends Sabre_MultiWebsites_Block_Seo_AlternateLang_Abstract
{

    protected function preCheck()
    {
        /* @var $currentProduct Mage_Catalog_Model_Product */
        /* @var $currentCategory Mage_Catalog_Model_Category */
        $currentProduct = Mage::registry('current_product');
        if (!$currentProduct || !$currentProduct->getId()) {
            return false;
        }
        return true;
    }

    protected function updateUrlRewriteCollection(Mage_Core_Model_Resource_Url_Rewrite_Collection $urlRewrites)
    {
        /* @var $currentProduct Mage_Catalog_Model_Product */
        /* @var $currentCategory Mage_Catalog_Model_Category */
        $currentProduct = Mage::registry('current_product');
        $currentCategory = Mage::registry('current_category');
        $urlRewrites->addFieldToFilter("product_id", $currentProduct->getId());
        if ($currentCategory && $currentCategory->getId()) {
            $urlRewrites->addFieldToFilter("category_id", $currentCategory->getId());
        }
    }

}