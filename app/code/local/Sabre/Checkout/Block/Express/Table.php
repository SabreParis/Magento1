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
class Sabre_Checkout_Block_Express_Table extends Mage_Catalog_Block_Product_Abstract
{

    /**
     * @var null|Mage_Catalog_Model_Resource_Product_Collection
     */
    protected $_products = null;

    protected function _toHtml()
    {
        if ($this->getProducts()->count()) {
            return parent::_toHtml();
        }

        return '';
    }

    /**
     * @return string
     */
    public function getExpressAddToCartUrl()
    {
        return $this->getUrl(
            'checkout/cart/expressAdd',
            [
                'product_id'                  => $this->getProduct()->getId(),
                Mage_Core_Model_Url::FORM_KEY => Mage::getSingleton('core/session')->getFormKey(),
            ]
        );
    }

    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProducts()
    {
        if ($this->_products === null) {
            // retrieve product with the same model and color
            $this->_products = Mage::getResourceModel('catalog/product_collection');
            $this->_products->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes());
            $this->_products->addAttributeToFilter('a_model', ['eq' => $this->getProduct()->getData('a_model')]);
            $this->_products->addAttributeToFilter('color', ['eq' => $this->getProduct()->getData('color')]);
            $this->_products->addPriceData();
            $this->_products->addTaxPercents();
            if ($mainCategory = Mage::helper('sabre_catalog')->getMainCategoryByProduct($this->getProduct())) {
                $this->_products->addCategoryFilter($mainCategory);
            }
            $this->_products->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());

            $_connection = $this->_products->getConnection();

            // force current product in first position
            $_currentCondition = $_connection->quoteInto('e.entity_id = ?', $this->getProduct()->getId());
            $this->_products
                ->getSelect()
                ->columns(['is_current' => $_connection->getCheckSql($_currentCondition, 1, 2)])
                ->order('is_current ASC');
            // force order by position
            $this->_products
                ->getSelect()
                ->order('cat_index.position ASC');

            // join with quote item
            if ($quoteId = Mage::getSingleton('checkout/session')->getQuote()->getId()) {
                $this->_products
                    ->getSelect()
                    ->joinLeft(
                        ['quote_item_table' => $this->_products->getTable('sales/quote_item')],
                        implode(' AND ', [
                            $_connection->quoteInto('quote_item_table.quote_id = ?', $quoteId),
                            'quote_item_table.product_id = e.entity_id',
                        ]),
                        [
                            'quote_item_qty' => new Zend_Db_Expr('FLOOR(quote_item_table.qty)'),
                            'quote_item_id'  => 'quote_item_table.item_id',
                        ]
                    );
            }

//            Mage::log($this->_products->getSelectSql(true));
        }

        return $this->_products;
    }

}
