<?php
/**
 * created : 10/03/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Shop
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */


/**
 *  Shop Group general form block
 *
 */
class Ayaline_Shop_Block_Adminhtml_Shop_Group_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{

    public function initForm()
    {
        $form = new Varien_Data_Form();

        $group = Mage::registry('current_ayaline_shop_group');

        $fieldset = $form->addFieldset(
            'shop_group_form',
            array(
                'legend' => Mage::helper('ayalineshop')->__('Shop Group'),
            )
        );

        if (Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_group/actions/update')
            || Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_group/actions/create')
        ) {

            $fieldset->addField(
                'name',
                'text',
                array(
                    'label'    => Mage::helper('adminhtml')->__('Name'),
                    'class'    => 'required-entry',
                    'required' => true,
                    'name'     => 'name',
                )
            );
            //Image
            $fieldset->addField(
                'icon',
                'image',
                array(
                    'label'    => Mage::helper('ayalineshop')->__('Image'),
                    'class'    => 'input-text',
                    'name'     => 'icon',
                    'required' => false,
                    'note'     => Mage::helper('ayalineshop')->__('Extensions allowed: jpg, jpeg, gif and png'),
                )
            );
        } else {
            $fieldset->addField(
                'name',
                'note',
                array(
                    'label'    => Mage::helper('adminhtml')->__('Name'),
                    'class'    => 'required-entry',
                    'required' => true,
                    'name'     => 'name',
                )
            );
        }


        $form->setValues($group->getData());
        $this->setForm($form);

        return $this;
    }

}
