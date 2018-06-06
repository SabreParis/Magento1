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
class Ayaline_Billboard_Block_Adminhtml_Billboard_Edit_Tab_Main extends Ayaline_Billboard_Block_Adminhtml_Billboard_Edit_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected function _prepareForm()
    {
        /* @var $model Ayaline_Billboard_Model_Billboard */
        $model = Mage::registry('ayaline_billboard_current_billboard_bo');

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('billboard_');
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('ayalinebillboard')->__('Information')));

        if ($model->getBillboardId()) {
            $fieldset->addField('billboard_id', 'hidden', array('name' => 'billboard_id'));
        }

        $fieldset->addField('title', 'text', array(
            'name'     => 'title',
            'label'    => Mage::helper('ayalinebillboard')->__('Title'),
            'title'    => Mage::helper('ayalinebillboard')->__('Title'),
            'required' => true,
            'disabled' => $this->_isElementDisabled,
        ));

        $fieldset->addField('identifier', 'text', array(
            'name'     => 'identifier',
            'label'    => Mage::helper('ayalinebillboard')->__('Identifier'),
            'title'    => Mage::helper('ayalinebillboard')->__('Identifier'),
            'required' => true,
            'class'    => 'validate-xml-identifier',
            'note'     => Mage::helper('ayalinebillboard')->__('must be unique'),
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'    => Mage::helper('ayalinebillboard')->__('Status'),
            'title'    => Mage::helper('ayalinebillboard')->__('Status'),
            'name'     => 'is_active',
            'required' => true,
            'options'  => array(
                1 => Mage::helper('adminhtml')->__('Enable'),
                0 => Mage::helper('adminhtml')->__('Disable'),
            ),
            'disabled' => $this->_isElementDisabled,
        ));

        $typeIdFieldType = 'select';
        $typeIdFieldLabelTitle = Mage::helper('ayalinebillboard')->__('Billboard Type');
        if (Mage::getSingleton('ayalinebillboard/system_config')->isActiveMultiType()) {
            $typeIdFieldType = 'multiselect';
            $typeIdFieldLabelTitle = Mage::helper('ayalinebillboard')->__('Billboard Types');
        }

        $fieldset->addField('type_id', $typeIdFieldType, array(
            'name'     => 'types[]',
            'label'    => $typeIdFieldLabelTitle,
            'title'    => $typeIdFieldLabelTitle,
            'required' => true,
            'values'   => Mage::getSingleton('ayalinebillboard/system_source_billboardType')->toOptionArray(),
            'disabled' => $this->_isElementDisabled,
        ));

        $fieldset->addField('display_from', 'date', array(
            'label'        => Mage::helper('ayalinebillboard')->__('Display from'),
            'required'     => true,
            'name'         => 'display_from',
            'image'        => $this->getSkinUrl('images/grid-cal.gif'),
            'format'       => $this->_dateTimeFormatIso,
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'time'         => true,
            'disabled'     => $this->_isElementDisabled,
        ));

        $fieldset->addField('display_to', 'date', array(
            'label'        => Mage::helper('ayalinebillboard')->__('Display to'),
            'required'     => true,
            'name'         => 'display_to',
            'image'        => $this->getSkinUrl('images/grid-cal.gif'),
            'format'       => $this->_dateTimeFormatIso,
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'time'         => true,
            'disabled'     => $this->_isElementDisabled,
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'     => 'stores[]',
                'label'    => Mage::helper('ayalinebillboard')->__('Store View'),
                'title'    => Mage::helper('ayalinebillboard')->__('Store View'),
                'required' => true,
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled' => $this->_isElementDisabled,
            ));
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'  => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('customer_group_id', 'multiselect', array(
            'name'     => 'customer_group_ids[]',
            'label'    => Mage::helper('ayalinebillboard')->__('Customer Groups'),
            'title'    => Mage::helper('ayalinebillboard')->__('Customer Groups'),
            'required' => true,
            'values'   => Mage::getSingleton('ayalinebillboard/system_source_customerGroup')->toOptionArray(),
            'disabled' => $this->_isElementDisabled,
        ));

        $fieldset->addField('widget_position', 'text', array(
            'name'     => 'widget_position',
            'label'    => Mage::helper('ayalinebillboard')->__('Position'),
            'title'    => Mage::helper('ayalinebillboard')->__('Position'),
            'required' => false,
            'class'    => 'validate-number',
            'disabled' => $this->_isElementDisabled,
            'note'     => Mage::helper('ayalinebillboard')->__('only used in widget'),
        ));

        Mage::dispatchEvent('ayalinebillboard_adminhtml_billboard_edit_tab_main_prepare_form', array('form'  => $form,
                                                                                                     'model' => $model
            ));

        if (!$model->hasData('display_from')) {
            $model->setData('display_from', now());
        }
        if (!$model->hasData('widget_position')) {
            $model->setData('widget_position', 0);
        }
        $form->setValues($model->getData());
        $this->setForm($form);

        return $this;
    }

    public function getTabLabel()
    {
        return Mage::helper('ayalinebillboard')->__('Information');
    }

    public function getTabTitle()
    {
        return Mage::helper('ayalinebillboard')->__('Information');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

}