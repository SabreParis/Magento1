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
 * Shop Group edit block
 *
 */
class Ayaline_Shop_Block_Adminhtml_Shop_Group_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_shop_group';
        $this->_blockGroup = 'ayalineshop';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('ayalineshop')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('ayalineshop')->__('Delete'));

        if (!Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_group/actions/update')) {
            $this->_removeButton('save');
            $this->_removeButton('reset');
            $this->_removeButton('delete');
        }
        if (!$this->getGroupId()) {
            $this->_removeButton('delete');
        }
    }

    public function getGroupId()
    {
        return Mage::registry('current_ayaline_shop_group')->getId();
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_ayaline_shop_group')->getId()) {
            return $this->htmlEscape(Mage::registry('current_ayaline_shop_group')->getName());
        } else {
            return Mage::helper('ayalineshop')->__('New Shop Group');
        }
    }

    public function getFormActionUrl()
    {
        return $this->getUrl('*/*/save');
    }

    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', array('_current' => true));
    }

    protected function _prepareLayout()
    {
        if (Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_group/actions/update')) {
            $this->_addButton(
                'save_and_continue',
                array(
                    'label'   => Mage::helper('ayalineshop')->__('Save and Continue Edit'),
                    'onclick' => 'saveAndContinueEdit(\'' . $this->_getSaveAndContinueUrl() . '\')',
                    'class'   => 'save',
                ),
                10
            );
        }

        return parent::_prepareLayout();
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl(
            '*/*/save',
            array(
                '_current' => true,
                'back'     => 'edit',
                'tab'      => '{{tab_id}}',
            )
        );
    }
}
