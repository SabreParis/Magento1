<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 04/01/2016
 * Time: 15:16
 */
class Sabre_Dataflow_Block_Config_Adminhtml_Form_Field_Website extends Mage_Core_Block_Html_Select
{

    public function _toHtml()
    {

        $websites = Mage::getModel("core/website")->getCollection();

        foreach ($websites as $website) {
            /* @var $website Mage_Core_Model_Website */
            $this->addOption($website->getCode(), $website->getName());
        }

        return parent::_toHtml();
    }

    public function setInputName($value)
    {
        return $this->setName($value);
    }

}