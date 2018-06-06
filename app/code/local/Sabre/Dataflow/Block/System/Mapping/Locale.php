<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 04/01/2016
 * Time: 15:01
 */
class Sabre_Dataflow_Block_System_Mapping_Locale extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $_itemMagentoWebsitesRenderer;

    public function _prepareToRender() {
        $this->addColumn('locale', array(
            'label' => Mage::helper('sabre_dataflow')->__('Locale'),
            'style' => 'width:100px',
        ));
        $this->addColumn('language', array(
            'label' => Mage::helper('sabre_dataflow')->__('Code langue (sabre)'),
            'style' => 'width:100px',
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('sabre_dataflow')->__('Add');
    }


}