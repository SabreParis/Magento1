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
class Ayaline_Billboard_Block_Adminhtml_Billboard_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_objectId = 'billboard_id';
        $this->_controller = 'adminhtml_billboard';
        $this->_blockGroup = 'ayalinebillboard';
        $this->_mode = 'edit';

        parent::__construct();

        if ($this->_isAllowedAction('save')) {
            $this->_updateButton('save', 'label', Mage::helper('ayalinebillboard')->__('Save Billboard'));
            $this->_addButton('saveandcontinue', array(
                'label'   => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit();',
                'class'   => 'save',
            ), -100);

            $this->_formScripts[] = " function saveAndContinueEdit(){ editForm.submit($('edit_form').action+'back/edit/'); } ";
        } else {
            $this->_removeButton('save');
        }

        if ($this->_isAllowedAction('delete')) {
            $this->_updateButton('delete', 'label', Mage::helper('ayalinebillboard')->__('Delete Billboard'));
        } else {
            $this->_removeButton('delete');
        }
    }

    public function getHeaderText()
    {
        if (Mage::registry('ayaline_billboard_current_billboard_bo') && Mage::registry('ayaline_billboard_current_billboard_bo')->getId()) {
            return Mage::helper('ayalinebillboard')->__("Edit Billboard '%s'", $this->htmlEscape(Mage::registry('ayaline_billboard_current_billboard_bo')->getTitle()));
        } else {
            return Mage::helper('ayalinebillboard')->__('New Billboard');
        }
    }

    public function getFormActionUrl()
    {
        return $this->getUrl('*/*/save');
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed(Ayaline_Billboard_Model_Billboard::IS_ALLOWED_BILLBOARD . $action);
    }

}