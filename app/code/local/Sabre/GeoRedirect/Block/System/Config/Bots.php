<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 04/01/2016
 * Time: 15:01
 */
class Sabre_GeoRedirect_Block_System_Config_Bots extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    public function _prepareToRender() {
        $this->addColumn('name', array(
            'label' => Mage::helper('sabre_georedirect')->__('Name'),
            'style' => 'width:150px',
        ));
        $this->addColumn('user_agent', array(
            'label' => Mage::helper('sabre_georedirect')->__('User Agent String'),
            //'style' => 'width:100px',
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('sabre_georedirect')->__('Add');
    }


}