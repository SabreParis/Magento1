<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 04/01/2016
 * Time: 15:01
 */
class Sabre_Dataflow_Block_System_Mapping_Website extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $_itemMagentoWebsitesRenderer;

    public function _prepareToRender() {
        $this->addColumn('sabre_website_code', array(
            'label' => Mage::helper('sabre_dataflow')->__('Sabre Website Code'),
            'style' => 'width:100px',
        ));
        $this->addColumn('magento_website_code', array(
            'label' => Mage::helper('sabre_dataflow')->__('Magento Website'),
            'renderer' => $this->_getMagentoWebsitesRenderer(),
        ));


        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('sabre_dataflow')->__('Add');
    }

    protected function _getMagentoWebsitesRenderer()
    {
        if (!$this->_itemMagentoWebsitesRenderer) {
            $this->_itemMagentoWebsitesRenderer = $this->getLayout()->createBlock(
                'sabre_dataflow/config_adminhtml_form_field_website', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_itemMagentoWebsitesRenderer;
    }

    protected function _prepareArrayRow(Varien_Object $row)
    {
        $row->setData(
            'option_extra_attr_' . $this->_getMagentoWebsitesRenderer()
                ->calcOptionHash($row->getData('magento_website_code')),
            'selected="selected"'
        );

    }



}