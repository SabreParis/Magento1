<?php

/**
 * created : 2016
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2016 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Catalog_Model_Observer
{

    const LAST_VISITED_GATEGORY_URL_KEY = '_last_visited_category_url';

    public function setLastVisitedCategoryUrl($observer)
    {
        if(Mage::app()->getRequest()->isAjax()){
            return $this;
        }

        $category = $observer->getEvent()->getCategory();
        $this->_setSessionCategoryUrl(
            $category->getId(),
            Mage::getUrl(null, array('_current'=>true,'_use_rewrite'=> true))
        );

    }

    public function setProductRefererCategoryUrl($observer)
    {
        /* @var $product Mage_Catalog_Model_Product */
        $product = $observer->getEvent()->getProduct();
        $lastVisitedCategUrl = $this->_getSessionCategoryUrl($product->getCategoryId());

        if(trim($lastVisitedCategUrl) == trim($this->_getRefererUrl())){
            $product->setCategoryRefererUrl($lastVisitedCategUrl);
            Mage::register('last_visited_category_url', $product->getCategoryRefererUrl(), true);

            return $this;
        }

        $productCategoryUrl = ($_category = $this->getProductCategory($product)) ? $_category->getUrl(): null;
        $product->setCategoryRefererUrl($productCategoryUrl);
        Mage::register('last_visited_category_url', $product->getCategoryRefererUrl(), true);

        return $this;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     */
    protected function getProductCategory($product){
        if($product->getCategory() && $product->getCategory()->getId()){

            return $product->getCategory();
        }

        $categeryCollection = $product->getCategoryCollection();
        $categeryCollection
            ->setCurPage(1)
            ->setPageSize(1);

        if($categeryCollection->getSize()){

            return $categeryCollection->getFirstItem();
        }

        return null;
    }

    protected function _getSessionCategoryUrl($categoryId){

        return Mage::getSingleton('core/session')
            ->getData($this->_getSessionCategoryUrlKey($categoryId));
    }

    protected function _setSessionCategoryUrl($categoryId, $url){

        return Mage::getSingleton('core/session')
            ->setData($this->_getSessionCategoryUrlKey($categoryId), $url);
    }

    protected function _getSessionCategoryUrlKey($categoryId){

        return self::LAST_VISITED_GATEGORY_URL_KEY . $categoryId;
    }


    /**
     * Identify referer url via all accepted methods (HTTP_REFERER, regular or base64-encoded request param)
     *
     * @return string
     */
    protected function _getRefererUrl()
    {
        $refererUrl = Mage::app()->getRequest()->getServer('HTTP_REFERER');
        if ($url = Mage::app()->getRequest()->getParam(Mage_Core_Controller_Varien_Action::PARAM_NAME_REFERER_URL)) {
            $refererUrl = $url;
        }
        if ($url = Mage::app()->getRequest()->getParam(Mage_Core_Controller_Varien_Action::PARAM_NAME_BASE64_URL)) {
            $refererUrl = Mage::helper('core')->urlDecodeAndEscape($url);
        }
        if ($url = Mage::app()->getRequest()->getParam(Mage_Core_Controller_Varien_Action::PARAM_NAME_URL_ENCODED)) {
            $refererUrl = Mage::helper('core')->urlDecodeAndEscape($url);
        }

        if (!$this->_isUrlInternal($refererUrl)) {
            $refererUrl = Mage::app()->getStore()->getBaseUrl();
        }
        return $refererUrl;
    }

    /**
     * Check url to be used as internal
     *
     * @param   string $url
     * @return  bool
     */
    protected function _isUrlInternal($url)
    {
        if (strpos($url, 'http') !== false) {
            /**
             * Url must start from base secure or base unsecure url
             */
            if ((strpos($url, Mage::app()->getStore()->getBaseUrl()) === 0)
                || (strpos($url, Mage::app()->getStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, true)) === 0)
            ) {
                return true;
            }
        }
        return false;
    }
}