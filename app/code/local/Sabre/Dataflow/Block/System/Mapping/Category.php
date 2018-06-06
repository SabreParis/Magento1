<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 04/01/2016
 * Time: 15:01
 */
class Sabre_Dataflow_Block_System_Mapping_Category extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $_itemAttributeSetRenderer;
    protected $_itemArticleAttributeRenderer;
    protected $_itemModelAttributeRenderer;
    protected $_itemCategoryRenderer;

    public function _prepareToRender() {
        $this->addColumn('sabre_category_code', array(
            'label' => Mage::helper('sabre_dataflow')->__('Sabre Category Code'),
            'style' => 'width:100px',
        ));
        $this->addColumn('magento_attribute_set', array(
            'label' => Mage::helper('sabre_dataflow')->__('Attribute Set'),
            'renderer' => $this->_getAttributeSetRenderer(),
        ));
        $this->addColumn('magento_article_attribute', array(
            'label' => Mage::helper('sabre_dataflow')->__('Article attribute'),
            'renderer' => $this->_getArticleAttributeRenderer(),
        ));
        $this->addColumn('magento_model_attribute', array(
            'label' => Mage::helper('sabre_dataflow')->__('Model attribute'),
            'renderer' => $this->_getModelAttributeRenderer(),
        ));
        $this->addColumn('magento_category', array(
            'label' => Mage::helper('sabre_dataflow')->__('Category'),
            'renderer' => $this->_getCategoryRenderer(),
        ));


        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('sabre_dataflow')->__('Add');
    }

    protected function _getAttributeSetRenderer()
    {
        if (!$this->_itemAttributeSetRenderer) {
            $this->_itemAttributeSetRenderer = $this->getLayout()->createBlock(
                'sabre_dataflow/config_adminhtml_form_field_attributeset', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_itemAttributeSetRenderer;
    }

    protected function _getArticleAttributeRenderer() {
        if (!$this->_itemArticleAttributeRenderer) {
            $this->_itemArticleAttributeRenderer = $this->getLayout()->createBlock(
                'sabre_dataflow/config_adminhtml_form_field_article', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_itemArticleAttributeRenderer;
    }

    protected function _getModelAttributeRenderer() {
        if (!$this->_itemModelAttributeRenderer) {
            $this->_itemModelAttributeRenderer = $this->getLayout()->createBlock(
                'sabre_dataflow/config_adminhtml_form_field_model', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_itemModelAttributeRenderer;
    }

    protected function _getCategoryRenderer() {
        if (!$this->_itemCategoryRenderer) {
            $this->_itemCategoryRenderer = $this->getLayout()->createBlock(
                'sabre_dataflow/config_adminhtml_form_field_category', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_itemCategoryRenderer;
    }

    protected function _prepareArrayRow(Varien_Object $row)
    {
        $row->setData(
            'option_extra_attr_' . $this->_getAttributeSetRenderer()
                ->calcOptionHash($row->getData('magento_attribute_set')),
            'selected="selected"'
        );
        $row->setData(
            'option_extra_attr_' . $this->_getArticleAttributeRenderer()
                ->calcOptionHash($row->getData('magento_article_attribute')),
            'selected="selected"'
        );
        $row->setData(
            'option_extra_attr_' . $this->_getModelAttributeRenderer()
                ->calcOptionHash($row->getData('magento_model_attribute')),
            'selected="selected"'
        );
        $row->setData(
            'option_extra_attr_' . $this->_getCategoryRenderer()
                ->calcOptionHash($row->getData('magento_category')),
            'selected="selected"'
        );
    }



}