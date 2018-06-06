<?php

/**
 * created : 2013
 *
 * @category  Ayaline
 * @package   Ayaline_EnhancedAdmin
 * @author    aYaline
 * @copyright Ayaline - 2013 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_EnhancedAdmin_Block_Adminhtml_Setup_View extends Ayaline_Core_Block_Adminhtml_Widget_Grids
{

    protected function _construct()
    {
        parent::_construct();
        /** @var $module Ayaline_EnhancedAdmin_Model_Module */
        $module = Mage::registry('ayaline_enhancedadmin_module');
        $this->_headerText = $this->__('Module %s (%s)', $module->getName(), $module->getVersion());
        $this->_addButton('back', array(
            'label'   => Mage::helper('adminhtml')->__('Back'),
            'onclick' => "setLocation('{$this->getBackUrl()}')",
            'class'   => 'back',
        ));


        if (Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('default_apply')
            || Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('custom_apply')
            || Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('check_setups')
            || Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('setup_class_cache')
        ) {

            $this->_addButton('actions', array(
                'label'   => $this->__('Actions'),
                'onclick' => "setupActions.open('{$this->getUrl('*/*/actions', array('_current' => true))}');",
                'class'   => 'go',
            ));
        }
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }

}