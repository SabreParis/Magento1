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
class Ayaline_Billboard_Block_Adminhtml_Billboard_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected $_isElementDisabled = true;
    protected $_dateTimeFormatIso;

    /**
     * Init form
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('billboard_form');
        $this->setTitle(Mage::helper('ayalinebillboard')->__('Billboard Information'));

        if ($this->_isAllowedAction('save')) {
            $this->_isElementDisabled = false;
        }

        $this->_dateTimeFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array('id'     => 'edit_form',
                                           'action' => $this->getData('action'),
                                           'method' => 'post'
                                     ));
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
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