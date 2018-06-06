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
 * Adminhtml shop edit form block
 *
 */
class Ayaline_Shop_Block_Adminhtml_Shop_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
                                         'id'      => 'edit_form',
                                         'action'  => $this->getData('action'),
                                         'method'  => 'post',
                                         'enctype' => 'multipart/form-data'
                                     ));

        $shop = Mage::registry('current_ayaline_shop');

        if ($shop->getId()) {
            $shop->setData('old_is_active', $shop->getIsActive());
            $form->addField('shop_id', 'hidden', array(
                'name' => 'shop_id',
            ));
            $form->addField('old_is_active', 'hidden', array(
                'name' => 'old_is_active'
            ));
            $form->addField('old_picture', 'hidden', array(
                'name' => 'old_picture'
            ));
            $form->addField('old_marker', 'hidden', array(
                'name' => 'old_marker'
            ));
            $form->setValues($shop->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
