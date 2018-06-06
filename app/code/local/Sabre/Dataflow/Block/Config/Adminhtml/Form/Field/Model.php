<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 04/01/2016
 * Time: 15:16
 */
class Sabre_Dataflow_Block_Config_Adminhtml_Form_Field_Model extends Mage_Core_Block_Html_Select
{

    public function _toHtml()
    {

        $attributes = Mage::getModel("eav/entity_attribute")->getCollection();
        $attributes->addFieldToFilter("entity_type_id", 4);
        $attributes->addFieldToFilter("attribute_code", array("like" => "a_model_%"));

        foreach ($attributes as $attribute) {
            $this->addOption($attribute->getData("attribute_code"), $attribute->getData("attribute_code"));
        }

        return parent::_toHtml();
    }

    public function setInputName($value)
    {
        return $this->setName($value);
    }

}