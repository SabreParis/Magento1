<?php
/**
 * created : 12/10/2015
 *
 * @category Ayaline
 * @package Ayaline_Billboard
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */


/**
 *  Shop Group general form block
 *
 */
class Sabre_Shop_Block_Adminhtml_Shop_Group_Edit_Tab_General extends Ayaline_Shop_Block_Adminhtml_Shop_Group_Edit_Tab_General
{
    public function initForm()
    {
        parent::initForm();

        $group = Mage::registry('current_ayaline_shop_group');

        /* @var $form Varien_Data_Form */
        $form = $this->getForm();

        /* @var $fieldset Varien_Data_Form_Element_Fieldset */
        $fieldset = $form->getElement('shop_group_form');

        //Marqueur
        $fieldset->addField('marker', 'image',
            array(
                'label'    => Mage::helper('ayalineshop')->__('Marker'),
                'name'     => 'marker',
                'required' => false,
                'note'     => Mage::helper('ayalineshop')->__('Extensions allowed: jpg, jpeg, gif and png') . ' ' . Mage::helper('ayalineshop')->__('Size : 30x30')
            ),
            'icon'
        );


        $form->setValues($group->getData());

        $this->setForm($form);

        return $this;

    }
}