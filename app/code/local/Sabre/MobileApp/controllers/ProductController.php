<?php

/**
 * created: 2016
 *
 * @category  XXXXXXX
 * @package   Ayaline
 * @author    aYaline Magento <support.magento-shop@ayaline.com>
 * @copyright 2016 - aYaline Magento
 * @license   aYaline - http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 * @link      http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_MobileApp_ProductController extends Sabre_MobileApp_Controller_Abstract
{

    
    public function indexAction()
    {
        try {
            $store = $this->_getStore();


            /** @var $collection Mage_Catalog_Model_Resource_Product_Collection */
            $collection = Mage::getResourceModel('catalog/product_collection');

            $collection->addStoreFilter($store->getId())
                       ->setStoreId($store->getId())
                       ->addPriceData()
                       ->addAttributeToSelect('*')
                       ->addAttributeToFilter(
                           'visibility',
                           array('neq' => Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)
                       )
                       ->addAttributeToFilter(
                           'status',
                           array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                       );
            $collection->setPage($this->_getPageNumber(), $this->_getPageSize());

            if ($date = $this->_getDate()) {
                $collection->addAttributeToFilter(
                    'updated_at',
                    [
                        'gteq' => $date
                    ]
                );
            }

            $this->_responseData['success']['page'] = $collection->getCurPage();
            $this->_responseData['success']['limit'] = $collection->getPageSize();

            $this->_responseData['success']['total'] = $collection->getConnection()->fetchOne($collection->getSelectCountSql());
            $this->_responseData['success']['count'] = $collection->count();


            foreach ($collection as $_product) {
                $this->_prepareProductForResponse($_product);
            }

            $this->_responseData['success']['data'] = $collection->toArray();
            
        } catch (\Exception $e) {
            $this->_responseStatus = false;
            $this->_responseData['error']['error']['message'] = 'An error occurred';
        }
    }

    protected function _getDate()
    {
        $date = $this->getRequest()->getQuery('date', false);
        if ($date) {
            try {
                $date = DateTime::createFromFormat(Varien_Date::DATE_PHP_FORMAT, $date);
                if (!$date) {
                    throw new Exception('no date');
                }
            } catch (Exception $e) {
                $date = new DateTime('now');
                $date->sub(new DateInterval('P1W')); // last week
            }
            $date->setTime(0, 0, 0);

            return $date->format(Varien_Date::DATETIME_PHP_FORMAT);
        }

        return false;
    }

    protected function _prepareProductForResponse(Mage_Catalog_Model_Product $product)
    {
        /** @var $productHelper Mage_Catalog_Helper_Product */
//        $productHelper = Mage::helper('catalog/product');
        /** @var Mage_Tax_Helper_Data $taxHelper */
        $taxHelper = Mage::helper('tax');

        $productData = $product->getData();
        $product->setWebsiteId($this->_getStore()->getWebsiteId());
        // customer group is required in product for correct prices calculation
        $product->setCustomerGroupId(Mage_Customer_Model_Group::NOT_LOGGED_IN_ID);
        // calculate prices
        $price = $product->getPrice();
        $finalPrice = $product->getFinalPrice();
        $productData['regular_price_with_tax'] = $taxHelper->getPrice($product, $price, true);
        $productData['regular_price_without_tax'] = $taxHelper->getPrice($product, $price, false);
        $productData['final_price_with_tax'] = $taxHelper->getPrice($product, $finalPrice, true);
        $productData['final_price_without_tax'] = $taxHelper->getPrice($product, $finalPrice, false);

        $productData['is_saleable'] = $product->getIsSalable();
        $productData['image_url'] = (string) Mage::helper('catalog/image')->init($product, 'image');
        $productData['category_ids'] = $product->getCategoryIds();


        // remove tier price from response
        $product->unsetData('tier_price');
        unset($productData['tier_price']);

        $product->addData($productData);
    }

}
