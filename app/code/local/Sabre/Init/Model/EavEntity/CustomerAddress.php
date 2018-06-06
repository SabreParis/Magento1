<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 01/02/2016
 * Time: 09:51
 */
class Sabre_Init_Model_EavEntity_CustomerAddress extends Sabre_Init_Model_EavEntity
{

    protected $_entityName = "customer_address";
    protected $_entityModelName = "customer/address";

    public $actionName = "Import des adresses des clients";

    protected function _init() {
        $this->_destEntityTableName = Mage::getSingleton('core/resource')->getTableName("customer/address_entity");
        $this->_srcEntityTableName = $this->_databaseSourcePrefix . "customer_address_entity";
    }

    protected function _transformRow($row)
    {
        return $row;
    }

}