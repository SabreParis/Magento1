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
class Ayaline_Billboard_Block_Adminhtml_Billboard_Edit_Tab_Content extends Ayaline_Billboard_Block_Adminhtml_Billboard_Edit_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected function _prepareForm()
    {
        /* @var $model Ayaline_Billboard_Model_Billboard */
        $model = Mage::registry('ayaline_billboard_current_billboard_bo');

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('game_');
        $fieldset = $form->addFieldset('base_fieldset', array(
                'legend' => Mage::helper('ayalinebillboard')->__('Content'),
                'class'  => 'fieldset-wide'
            ));

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(array('tab_id' => $this->getTabId()));

        if ($model->getGameId()) {
            $fieldset->addField('billboard_id', 'hidden', array('name' => 'billboard_id'));
        }

        // Setting custom renderer for content field to remove label column
        $renderer = $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset_element')->setTemplate('cms/page/edit/form/renderer/content.phtml');
        $fieldset->addField('content', 'editor', array(
            'name'     => 'content',
            'style'    => 'height:36em;',
            'required' => true,
            'disabled' => $this->_isElementDisabled,
            'config'   => $wysiwygConfig
        ))->setRenderer($renderer);

        $form->setValues($model->getData());
        $this->setForm($form);

        return $this;
    }

    public function getTabLabel()
    {
        return Mage::helper('ayalinebillboard')->__('Content');
    }

    public function getTabTitle()
    {
        return Mage::helper('ayalinebillboard')->__('Content');
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