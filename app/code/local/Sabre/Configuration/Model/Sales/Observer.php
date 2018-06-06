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
class Sabre_Configuration_Model_Sales_Observer
{

    public function setProductAttributesToQuoteItem($observer)
    {
        /* @var $quoteItem Mage_Sales_Model_Quote_Item */
        $product = $observer->getEvent()->getProduct();
        $quoteItem = $observer->getEvent()->getQuoteItem();

        // Change name to custom product name
        $quoteItem->setName(Mage::helper('sabre_catalog')->getCustomProductName($product));

        // Add product attributes
        $quoteItem->setData('a_is_set', $product->getData('a_is_set'));
        $quoteItem->setData('a_article', $product->getData('a_article'));
        $quoteItem->setData('a_model', $product->getData('a_model'));
        $quoteItem->setData('color', $product->getData('color'));

        return $this;
    }
}
