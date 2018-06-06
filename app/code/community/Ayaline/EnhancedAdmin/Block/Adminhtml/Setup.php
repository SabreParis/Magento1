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
class Ayaline_EnhancedAdmin_Block_Adminhtml_Setup extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct(array $args = array())
    {
        $this->_controller = 'adminhtml_setup';
        $this->_blockGroup = 'ayaline_enhancedadmin';
        $this->_headerText = $this->__('Setup Management');
        parent::__construct($args);
        $this->_removeButton('add');

        if (Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('default_apply')
            || Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('custom_apply')
            || Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('check_setups')
            || Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('setup_class_cache')
        ) {

            $this->_addButton('actions', array(
                'label'   => $this->__('Actions'),
                'onclick' => "setupActions.open('{$this->getUrl('*/*/actions')}');",
                'class'   => 'go',
            ));
        }
    }

}