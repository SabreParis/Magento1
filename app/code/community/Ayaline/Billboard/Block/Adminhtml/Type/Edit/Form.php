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
class Ayaline_Billboard_Block_Adminhtml_Type_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{


    /**
     * Init form
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('type_form');
        $this->setTitle(Mage::helper('ayalinebillboard')->__('Billboard Type Information'));
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('ayaline_billboard_current_type_bo');

        $form = new Varien_Data_Form(array(
                                         'id'     => 'edit_form',
                                         'action' => $this->getData('action'),
                                         'method' => 'post'
                                     )
        );

        $form->setHtmlIdPrefix('type_');

        $fieldset = $form->addFieldset('base_fieldset', array(
                'legend' => Mage::helper('ayalinebillboard')->__('General Information'),
                'class'  => 'fieldset-wide'
            ));

        if ($model->getTypeId()) {
            $fieldset->addField('type_id', 'hidden', array('name' => 'type_id'));
            $fieldset->addField('identifier', 'hidden', array('name' => 'identifier'));
        }

        $fieldset->addField('title', 'text', array(
            'name'     => 'title',
            'label'    => Mage::helper('ayalinebillboard')->__('Title'),
            'title'    => Mage::helper('ayalinebillboard')->__('Title'),
            'required' => true,
        ));

        if (!$model->getTypeId()) {
            $fieldset->addField('identifier', 'text', array(
                'name'     => 'identifier',
                'label'    => Mage::helper('ayalinebillboard')->__('Identifier'),
                'title'    => Mage::helper('ayalinebillboard')->__('Identifier'),
                'required' => true,
                'class'    => 'validate-xml-identifier',
                'note'     => Mage::helper('ayalinebillboard')->__('must be unique'),
            ));
        } else {
            $model->setIdentifierInfos($model->getIdentifier());
            $fieldset->addField('identifier_infos', 'label', array(
                'name'  => 'identifier_infos',
                'label' => Mage::helper('ayalinebillboard')->__('Identifier'),
                'title' => Mage::helper('ayalinebillboard')->__('Identifier'),
            ));
        }

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}