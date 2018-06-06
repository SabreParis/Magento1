<?php

/**
 * created : 09/10/2015
 *
 * @category Ayaline
 * @package Ayaline_Billboard
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Shop_Block_Adminhtml_Shop_Edit_Tab_General extends Ayaline_Shop_Block_Adminhtml_Shop_Edit_Tab_General
{
    /**
     *
     * @return \Sabre_Shop_Block_Adminhtml_Shop_Edit_Tab_General
     */
    public function initForm()
    {
        parent::initForm();
        $shop = Mage::registry('current_ayaline_shop');

        /* @var $form Varien_Data_Form */
        $form = $this->getForm();

        /* @var $fieldset Varien_Data_Form_Element_Fieldset */
        $fieldset = $form->getElement('shop_form');


        $isElementDisabled = false;
        if (!Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_shop/actions/update')
            && !Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_shop/actions/create')
        ) {
            $isElementDisabled = true;
        }

        //livraison
        $fieldset->addField('used_for_shipping', 'select', array(
            'label' => Mage::helper('ayalineshop')->__('used for shipping'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'used_for_shipping',
            'options' => array(
                true => Mage::helper('adminhtml')->__('Yes'),
                false => Mage::helper('adminhtml')->__('No')
            ),
            'disabled' => $isElementDisabled
        ),
            'street2'
        );
        $form->setValues($shop->getData());
        $this->setForm($form);

        return $this;
    }
}