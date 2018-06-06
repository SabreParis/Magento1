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
class Ayaline_Billboard_Block_Adminhtml_Type extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_type';
        $this->_blockGroup = 'ayalinebillboard';
        $this->_headerText = Mage::helper('ayalinebillboard')->__('Manage Billboard Types');
        $this->_addButtonLabel = Mage::helper('ayalinebillboard')->__('Create New Billboard Type');
        parent::__construct();
        if (!Mage::getSingleton('admin/session')->isAllowed(Ayaline_Billboard_Model_Billboard_Type::IS_ALLOWED_BILLBOARD_TYPE . 'save')) {
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