<?php
/**
 * created : 10/03/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Shop
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */


/**
 *  admin shop group left menu
 *
 */
class Ayaline_Shop_Block_Adminhtml_Shop_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('shop_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('ayalineshop')->__('Shop'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general', array(
            'label'   => Mage::helper('ayalineshop')->__('General'),
            'content' => $this->getLayout()->createBlock('ayalineshop/adminhtml_shop_edit_tab_general')->initForm()->toHtml(),
            'active'  => Mage::registry('current_ayaline_shop')->getId() ? false : true
        ));

        $this->addTab('schedules', array(
            'label'   => Mage::helper('ayalineshop')->__('Schedules'),
            'content' => $this->getLayout()->createBlock('ayalineshop/adminhtml_shop_edit_tab_schedules')->toHtml()
        ));

        $this->addTab('specilas-schedules', array(
            'label'   => Mage::helper('ayalineshop')->__('Specials Schedules'),
            'content' => $this->getLayout()->createBlock('ayalineshop/adminhtml_shop_edit_tab_specialsSchedules')->toHtml()
        ));

        $this->addTab('postcodes', array(
            'label'   => Mage::helper('ayalineshop')->__('Zones de chalandise'),
            'content' => $this->getLayout()->createBlock('ayalineshop/adminhtml_shop_edit_tab_postcodes')->toHtml()
        ));

        $this->_updateActiveTab();
        Varien_Profiler::stop('ayalineshop_shop/tabs');

        return parent::_beforeToHtml();
    }

    protected function _updateActiveTab()
    {
        $tabId = $this->getRequest()->getParam('tab');
        if ($tabId) {
            $tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
            if ($tabId) {
                $this->setActiveTab($tabId);
            }
        }
    }
}
