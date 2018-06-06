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
 * Adminhtml shop group block
 *
 */
class Ayaline_Shop_Block_Adminhtml_Shop_Group extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_shop_group';
        $this->_blockGroup = 'ayalineshop';
        $this->_headerText = Mage::helper('ayalineshop')->__('Manage Shop Group');
        $this->_addButtonLabel = Mage::helper('ayalineshop')->__('Create New Shop Group');
        parent::__construct();
        if (!Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_group/actions/create')) {
            $this->_removeButton('add');
        }
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/*/new');
    }

}
