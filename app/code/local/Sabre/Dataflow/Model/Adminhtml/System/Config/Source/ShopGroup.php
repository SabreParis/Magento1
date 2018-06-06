<?php

class Sabre_Dataflow_Model_Adminhtml_System_Config_Source_ShopGroup
{
    protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = array();
            $groups = Mage::getModel("ayalineshop/shop_group")->getCollection();
            foreach ($groups as $group) {
                $id = $group->getId();
                $name = $group->getName();
                $this->_options[] = array('value'=>$id, 'label'=>$name);
            }
        }
        return $this->_options;
    }
}
