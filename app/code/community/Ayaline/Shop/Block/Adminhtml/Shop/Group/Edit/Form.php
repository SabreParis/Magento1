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
 * Adminhtml shop group edit form block
 *
 */
class Ayaline_Shop_Block_Adminhtml_Shop_Group_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
                                         'id'      => 'edit_form',
                                         'action'  => $this->getData('action'),
                                         'method'  => 'post',
                                         'enctype' => 'multipart/form-data',
                                     ));

        $group = Mage::registry('current_ayaline_shop_group');

        if ($group->getId()) {
            $form->addField(
                'group_id',
                'hidden',
                array(
                    'name' => 'group_id',
                )
            );
            $form->addField(
                'old_icon',
                'hidden',
                array(
                    'name' => 'old_icon',
                )
            );
            $form->setValues($group->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
