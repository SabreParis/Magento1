<?php
/**
 * created : 10/08/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 *
 * @package Ayaline_Billboard
 */
class Ayaline_Billboard_Model_Observer
{

    /**
     * Add billboard tab in category form
     * Event : adminhtml_catalog_category_tabs (adminhtml)
     *
     * @param Varien_Event_Observer $observer
     * @return Ayaline_Billboard_Model_Observer
     */
    public function addTabInCategory($observer)
    {
        /* @var $tabs Mage_Adminhtml_Block_Catalog_Category_Tabs */
        $tabs = $observer->getEvent()->getTabs();
        $tabs->addTab('ayalinebillboard_billboard_category', array(
            'label' => Mage::helper('ayalinebillboard')->__('Billboard'),
            'url'   => Mage::getUrl('*/ayaline_billboard_category/billboard', array('_current' => true)),
            'class' => 'ajax',
        ));

        return $this;
    }

    /**
     * Set billboard / position data to category
     * Event : catalog_category_prepare_save (adminhtml)
     *
     * @param Varien_Event_Observer $observer
     * @return Ayaline_Billboard_Model_Observer
     */
    public function prepareSaveBillboardCategory($observer)
    {
        /* @var $category Mage_Catalog_Model_Category */
        $category = $observer->getEvent()->getCategory();
        /* @var $request Mage_Core_Controller_Request_Http */
        $request = $observer->getEvent()->getRequest();

        $links = $request->getPost('links', array());
        if (array_key_exists('billboard', $links)) {
            $billboards = array();
            foreach (Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['billboard']) as $_billboardId => $_position) {
                $billboards[$_billboardId] = (array_key_exists('position', $_position) && $_position['position'] != '') ? $_position['position'] : 0;
            }
            $category->setBillboards($billboards);
        }

        return $this;
    }

    /**
     * Save billboard - position / category association
     * Event : catalog_category_save_after (adminhtml)
     *
     * @param Varien_Event_Observer $observer
     * @return Ayaline_Billboard_Model_Observer
     */
    public function saveBillboardCategory($observer)
    {
        /* @var $category Mage_Catalog_Model_Category */
        $category = $observer->getEvent()->getCategory();
        Mage::getResourceSingleton('ayalinebillboard/billboard')->saveBillboardToCategory($category);

        return $this;
    }

    /**
     * Add billboard tab in product form
     * Event : ayaline_core_adminhtml_catalog_product_tabs_initializer (adminhtml)
     *
     * @param Varien_Event_Observer $observer
     * @return Ayaline_Billboard_Model_Observer
     */
    public function addTabInProduct($observer)
    {
        /* @var $block Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs */
        $block = $observer->getEvent()->getTabs();
        $block->addTab('ayaline_billboard_product', array(
            'label' => Mage::helper('ayalinebillboard')->__('Billboard'),
            'url'   => Mage::getUrl('*/ayaline_billboard_product/billboard', array('_current' => true)),
            'class' => 'ajax',
        ));

        return $this;
    }

    /**
     * Set billboard / position data to product
     * Event : catalog_product_prepare_save (adminhtml)
     *
     * @param Varien_Event_Observer $observer
     * @return Ayaline_Billboard_Model_Observer
     */
    public function prepareSaveBillboardProduct($observer)
    {
        /* @var $product Mage_Catalog_Model_Product */
        $product = $observer->getEvent()->getProduct();
        /* @var $request Mage_Core_Controller_Request_Http */
        $request = $observer->getEvent()->getRequest();

        $links = $request->getPost('links', array());
        if (array_key_exists('billboard', $links)) {
            $billboards = array();
            foreach (Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['billboard']) as $_billboardId => $_position) {
                $billboards[$_billboardId] = (array_key_exists('position', $_position) && $_position['position'] != '') ? $_position['position'] : 0;
            }
            $product->setBillboards($billboards);
        }

        return $this;
    }

    /**
     * Save billboard - position / product association
     * Event : catalog_product_save_after (adminhtml)
     *
     * @param Varien_Event_Observer $observer
     * @return Ayaline_Billboard_Model_Observer
     */
    public function saveBillboardProduct($observer)
    {
        /* @var $product Mage_Catalog_Model_Product */
        $product = $observer->getEvent()->getProduct();
        Mage::getResourceSingleton('ayalinebillboard/billboard')->saveBillboardToProduct($product);

        return $this;
    }

}