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
 * Adminhtml shop block
 *
 */
class Ayaline_Shop_Block_Adminhtml_Shop extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_shop';
        $this->_blockGroup = 'ayalineshop';
        $this->_headerText = Mage::helper('ayalineshop')->__('Manage Shops');
        $this->_addButtonLabel = Mage::helper('ayalineshop')->__('Create New Shop');
        parent::__construct();
        if (!Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_shop/actions/create')) {
            $this->_removeButton('add');
        }
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/*/new');
    }

}
