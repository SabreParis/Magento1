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
 *  Shop schedules form block
 *
 */
class Ayaline_Shop_Block_Adminhtml_Shop_Edit_Tab_Schedules extends Mage_Adminhtml_Block_Widget
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('ayaline/shop/edit/tab/schedules.phtml');
    }

    /**
     * Preparing layout, adding buttons
     *
     * @return Mage_Eav_Block_Adminhtml_Attribute_Edit_Options_Abstract
     */
    protected function _prepareLayout()
    {
        $this->setChild('delete_button',
                        $this->getLayout()->createBlock('adminhtml/widget_button')
                             ->setData(array(
                                           'label' => Mage::helper('eav')->__('Delete'),
                                           'class' => 'delete delete-option'
                                       )));

        $this->setChild('add_button',
                        $this->getLayout()->createBlock('adminhtml/widget_button')
                             ->setData(array(
                                           'label' => Mage::helper('eav')->__('Add Option'),
                                           'class' => 'add',
                                           'id'    => 'add_new_option_button'
                                       )));

        return parent::_prepareLayout();
    }

    /**
     * Retrieve HTML of delete button
     *
     * @return string
     */
    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    /**
     * Retrieve HTML of add button
     *
     * @return string
     */
    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    public function getSchedules()
    {
        return $this->getStore()->getSchedules(true);
    }

    /**
     *
     * @return Ayaline_Shop_Model_Shop
     */
    public function getStore()
    {
        return Mage::registry('current_ayaline_shop');
    }

    public function getReadOnly()
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_shop/actions/update')
            && !Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_shop/actions/create')
        ) {
            return true;
        }

        return false;
    }

}
