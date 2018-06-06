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
class Ayaline_Billboard_Block_Adminhtml_Billboard extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_billboard';
        $this->_blockGroup = 'ayalinebillboard';
        $this->_headerText = Mage::helper('ayalinebillboard')->__('Manage Billboards');
        $this->_addButtonLabel = Mage::helper('ayalinebillboard')->__('Create New Billboard');
        parent::__construct();
        /* @var $types Ayaline_Billboard_Model_Mysql4_Billboard_Type_Collection */
        $types = Mage::getResourceSingleton('ayalinebillboard/billboard_type_collection');
        if (!Mage::getSingleton('admin/session')->isAllowed(Ayaline_Billboard_Model_Billboard::IS_ALLOWED_BILLBOARD . 'save') || $types->count() == 0) {
            $this->_removeButton('add');
        }
    }

    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    public function isSingleStoreMode()
    {
        return Mage::app()->isSingleStoreMode();
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/*/new');
    }

}