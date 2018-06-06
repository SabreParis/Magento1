<?php

/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Checkout_Block_Cart_Sidebar extends Mage_Checkout_Block_Cart_Sidebar
{

    protected $_sortedItems = null;

    protected function _getSortedItems()
    {
        if ($this->_sortedItems === null) {
            $items = Mage::getResourceModel('sales/quote_item_collection');
            $items->setOrder('updated_at');
            $items->setQuote($this->getQuote());
            foreach ($items as $_item) {
                if (!$_item->isDeleted() && !$_item->getParentItemId()) {
                    $this->_sortedItems[] =  $_item;
                }
            }
        }
        return $this->_sortedItems;
    }

    public function getItems()
    {
        return $this->_getSortedItems();
    }

}
