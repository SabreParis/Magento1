<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 23/12/2016
 * Time: 12:24
 */
class Sabre_MultiWebsites_Block_Seo_AlternateLang_Category extends Sabre_MultiWebsites_Block_Seo_AlternateLang_Abstract
{
    protected function preCheck()
    {
        /* @var $currentCategory Mage_Catalog_Model_Category */
        $currentCategory = Mage::registry('current_category');
        if (!$currentCategory || !$currentCategory->getId()) {
            return false;
        }
        return true;
    }

    protected function updateUrlRewriteCollection(Mage_Core_Model_Resource_Url_Rewrite_Collection $urlRewrites)
    {
        /* @var $currentCategory Mage_Catalog_Model_Category */
        $currentCategory = Mage::registry('current_category');
        $urlRewrites->addFieldToFilter("category_id", $currentCategory->getId());
    }

}