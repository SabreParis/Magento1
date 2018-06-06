<?php

/**
 * Created : 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Configuration_Model_OldProductUrl_Observer
{

    protected $useLike = false;

    /**
     *
     * @param Mixed $observer
     * @return \Sabre_Configuration_Model_OldProductUrl_Observer
     * @event controller_action_predispatch_cms_index_noRoute
     */
    public function redirect($observer)
    {
        /* @var $action Mage_Core_Controller_Front_Action */
        $action = $observer->getEvent()->getControllerAction();

        $matchedSku = $this->_getSkuFromPath($action->getRequest()->getPathInfo());

        if ($matchedSku) {
            /** @var Mage_Catalog_Model_Product $product */
            $product = null;


            /** @var Mage_Catalog_Model_Resource_Product_Collection $productCollection */
            $productCollection = Mage::getResourceModel('catalog/product_collection');
            $productCollection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                              ->addPriceData()
                              ->addUrlRewrite()
                              ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds())
                              ->setPageSize(1);

            if ($this->useLike) {
                $productCollection->addAttributeToFilter('sku', ['like' => "{$matchedSku}-%"]);

                $product = $productCollection->getFirstItem();
            } else {
                $productId = Mage::getResourceSingleton('catalog/product')->getIdBySku($matchedSku);
                if ($productId) {
                    $productCollection->addIdFilter($productId);

                    $product = $productCollection->getFirstItem();
                }
            }

            if ($product && $product->getId()) {
                $action->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                $action->getResponse()
                       ->setRedirect($product->getProductUrl(), 301)
                       ->sendResponse();
            }
        }

        return $this;
    }

    /**
     *
     * @param string $pathInfo
     * @return null|string
     */
    protected function _getSkuFromPath($pathInfo)
    {
        $trimmedPathInfo = trim($pathInfo, '/');

        /**
         * Sample input:
         *  c-1369-9-9005
         *  i-1588-4-9095
         *  va-2092-210-9105
         *  c-543-60-9085
         */
        $skuPattern = '#^([a-z]{1,2})-([0-9]{1,4})-([0-9]{1,4})-([0-9]{1,4})#';
        if (preg_match($skuPattern, $trimmedPathInfo, $matches) && (count($matches) === 5)) {

            $codeModel = strtoupper($matches[1]);
            $idModel = str_pad($matches[2], 4, 0, STR_PAD_LEFT);
            $idArticle = str_pad($matches[3], 3, 0, STR_PAD_LEFT);

            $this->useLike = true;

            /**
             * We ignore last part (color)
             *
             * Sample output:
             *  c-1369-9-9005 ==> C-1369-009
             *  i-1588-4-9095 ==> I-1588-004
             *  va-2092-210-9105 ==> VA-2092-210
             *  c-543-60-9085 ==> C-0543-060
             */
            return "{$codeModel}-{$idModel}-{$idArticle}";
        }

        /**
         * $patternMatchStart : Matching the begining of path url
         * Matches path ex: "/AB-1234-XXXXXXXX.html" ==> sku is : AB-1234
         */
        $patternMatchStart = "/\\/([a-zA-Z]+\\-[0-9]+)\\-[^\\/]+\\.html$/";

        /**
         * $patternMatchStart : Matching the begining of path url
         * Matches patern ex: "/XXXXXXXX-AB-1234.html" ==> sku is : AB-1234
         */
        $patternMatchEnd = "/\\-([a-zA-Z]+\\-[0-9]+)\\.html$/";

        $matchedResult = null;
        $matchedSku = null;
        if (preg_match($patternMatchStart, $pathInfo, $matchedResult)) {
            $matchedSku = array_pop($matchedResult);
        } elseif (preg_match($patternMatchEnd, $pathInfo, $matchedResult)) {
            $matchedSku = array_pop($matchedResult);
        }

        return $matchedSku;
    }
}
