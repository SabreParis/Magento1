<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 04/01/2016
 * Time: 15:16
 */
class Sabre_Dataflow_Block_Config_Adminhtml_Form_Field_Attributeset extends Mage_Core_Block_Html_Select
{

    public function _toHtml()
    {

        $attributeSets = Mage::getModel("eav/entity_attribute_set")->getCollection();
        $attributeSets->addFieldToFilter("entity_type_id", 4);

        foreach ($attributeSets as $attributeSet) {
            $this->addOption($attributeSet->getData("attribute_set_name"), $attributeSet->getData("attribute_set_name"));
        }

        return parent::_toHtml();
    }

    public function setInputName($value)
    {
        return $this->setName($value);
    }

}