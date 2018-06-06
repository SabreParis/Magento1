<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 04/01/2016
 * Time: 15:16
 */
class Sabre_Dataflow_Block_Config_Adminhtml_Form_Field_Category extends Mage_Core_Block_Html_Select
{

    public function _toHtml()
    {
        /* @var $categories Mage_Catalog_Model_Resource_Category_Collection */
        $categories = Mage::getModel("catalog/category")->getCollection();
        $categories->addFieldToFilter("level", 2);
        $categories->addAttributeToSelect("name");

        foreach ($categories as $category) {
            $this->addOption($category->getId(), $category->getData("name"));
        }

        return parent::_toHtml();
    }

    public function setInputName($value)
    {
        return $this->setName($value);
    }

}