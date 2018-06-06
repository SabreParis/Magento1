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

class Sabre_Shop_Block_Adminhtml_Shop_Group_Edit_Form extends Ayaline_Shop_Block_Adminhtml_Shop_Group_Edit_Form
{

    protected function _prepareForm()
    {
        parent::_prepareForm();

        $form = $this->getForm();
        $group = Mage::registry('current_ayaline_shop_group');

        if ($group->getId()) {
            $form->addField('old_marker', 'hidden', array(
                'name' => 'old_marker'
            ));
            $form->setValues($group->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return $this;
    }
}