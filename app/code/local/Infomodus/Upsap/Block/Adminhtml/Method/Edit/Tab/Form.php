<?php
/*
 * Author Rudyuk Vitalij Anatolievich
 * Email rvansp@gmail.com
 * Blog www.cervic.info
 */
?>
<?php

class Infomodus_Upsap_Block_Adminhtml_Method_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        /*multistore*/
        $store = $this->getRequest()->getParam('store', 1);
        /*multistore*/
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('method_form', array('legend' => Mage::helper('upsap')->__('UPS Access Point method information')));

        $fieldset->addField('title', 'text', array(
            'name' => 'title',
            'label' => Mage::helper('upsap')->__('Title'),
            'title' => Mage::helper('upsap')->__('Title'),
            'required' => true,
            'after_element_html' => '<p class="nm"><small>' . Mage::helper('upsap')->__('It appears only in admin interface') . '</small></p>',
        ));

        $fieldset->addField('name', 'text', array(
            'name' => 'name',
            'label' => Mage::helper('upsap')->__('Method name'),
            'title' => Mage::helper('upsap')->__('Method name'),
            'required' => true,
        ));

        $fieldset->addField('upsmethod_id', 'select', array(
            'name' => 'upsmethod_id',
            'label' => Mage::helper('upsap')->__('UPS Shipping method'),
            'title' => Mage::helper('upsap')->__('UPS Shipping method'),
            'required' => true,
            'values' => Mage::getModel('upslabel/config_upsmethod')->toOptionArray(),
        ));

        $isDinamic = $fieldset->addField('dinamic_price', 'select', array(
            'name' => 'dinamic_price',
            'label' => Mage::helper('upsap')->__('Price Source'),
            'title' => Mage::helper('upsap')->__('Price Source'),
            'values' => Mage::getModel('upsap/config_dinamicPrice')->toOptionArray(),
        ));

        $price = $fieldset->addField('price', 'text', array(
            'name' => 'price',
            'label' => Mage::helper('upsap')->__('Price'),
            'title' => Mage::helper('upsap')->__('Price'),
        ));

        $negotiated = $fieldset->addField('negotiated', 'select', array(
            'name' => 'negotiated',
            'label' => Mage::helper('upsap')->__('Negotiated rates'),
            'title' => Mage::helper('upsap')->__('Negotiated rates'),
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $negotiatedAmountFrom = $fieldset->addField('negotiated_amount_from', 'text', array(
            'name' => 'negotiated_amount_from',
            'label' => Mage::helper('upsap')->__('Order amount from for Negotiated rates'),
            'title' => Mage::helper('upsap')->__('Order amount from for Negotiated rates'),
            'value' => 0,
        ));

        $fieldset->addField('amount_min', 'text', array(
            'name' => 'amount_min',
            'label' => Mage::helper('upsap')->__('Minimum Order Amount'),
            'title' => Mage::helper('upsap')->__('Minimum Order Amount'),
            'value' => 0,
        ));
        $fieldset->addField('amount_max', 'text', array(
            'name' => 'amount_max',
            'label' => Mage::helper('upsap')->__('Maximum Order Amount'),
            'title' => Mage::helper('upsap')->__('Maximum Order Amount'),
            'value' => 0,
            'after_element_html' => '<p class="note"><span>' . Mage::helper('upsap')->__('If 0 then infinity') . '</span></p>',
        ));
        $fieldset->addField('tax', 'select', array(
            'name' => 'tax',
            'label' => Mage::helper('upsap')->__('Tax'),
            'title' => Mage::helper('upsap')->__('Tax'),
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));
        $fieldset->addField('showifnot', 'select', array(
            'name' => 'showifnot',
            'label' => Mage::helper('upsap')->__('Show Method if Not Applicable'),
            'title' => Mage::helper('upsap')->__('Show Method if Not Applicable'),
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));
        $fieldset->addField('country_ids', 'multiselect', array(
            'name' => 'country_ids',
            'label' => Mage::helper('upsap')->__('Allowed Countries'),
            'title' => Mage::helper('upsap')->__('Allowed Countries'),
            'required' => true,
            'values' => Mage::getModel('adminhtml/system_config_source_country')->toOptionArray(),
        ));
        /*$timeintransit = $fieldset->addField('timeintransit', 'select', array(
            'name' => 'timeintransit',
            'label' => Mage::helper('upsap')->__('Time in transit'),
            'title' => Mage::helper('upsap')->__('Time in transit'),
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));
        $addday = $fieldset->addField('addday', 'text', array(
            'name' => 'addday',
            'label' => Mage::helper('upsap')->__('Additional days'),
            'title' => Mage::helper('upsap')->__('Additional days'),
            'value' => 0,
        ));*/
        /*multistore*/
        $fieldset->addField('store_id', 'select', array(
            'name' => 'store_id',
            'label' => Mage::helper('upsap')->__('Apply to Store'),
            'value' => $store,
            'values' => Mage::getModel('upslabel/config_pickup_stores')->toOptionArray(),
            /*'disabled' => true,*/
        ));
        /*multistore*/

        $fieldset->addField('status', 'select', array(
            'name' => 'status',
            'label' => Mage::helper('upsap')->__('Enabled'),
            'title' => Mage::helper('upsap')->__('Enabled'),
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $this->setChild('form_after', $this->getLayout()
                ->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap($price->getHtmlId(), $price->getName())
                ->addFieldMap($isDinamic->getHtmlId(), $isDinamic->getName())
                ->addFieldDependence($price->getName(), $isDinamic->getName(), 0)
                ->addFieldMap($negotiated->getHtmlId(), $negotiated->getName())
                ->addFieldDependence($negotiated->getName(), $isDinamic->getName(), 1)
                ->addFieldMap($negotiatedAmountFrom->getHtmlId(), $negotiatedAmountFrom->getName())
                ->addFieldDependence($negotiatedAmountFrom->getName(), $negotiated->getName(), 1)
                /*->addFieldMap($timeintransit->getHtmlId(), $timeintransit->getName())
                ->addFieldMap($addday->getHtmlId(), $addday->getName())
                ->addFieldDependence($addday->getName(), $timeintransit->getName(), 1)*/
        );
        if (Mage::getSingleton('adminhtml/session')->getAccountData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getAccountData());
            Mage::getSingleton('adminhtml/session')->setAccountData(null);
        } elseif (Mage::registry('method_data') && count(Mage::registry('method_data')->getData()) > 0) {
            $data = Mage::registry('method_data')->getData();
            $form->setValues($data);
        }
        return parent::_prepareForm();
    }
}