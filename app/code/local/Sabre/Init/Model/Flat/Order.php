<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 02/02/2016
 * Time: 09:34
 */
class Sabre_Init_Model_Flat_Order extends Sabre_Init_Model_Flat
{

    public $actionName = "Import des commandes";

    //protected $_modulo = 100;

    public function transformOrderRow($row)
    {
        $anonymise = Mage::getStoreConfig("sabre_init/params/anonymise");
        if ($anonymise) {
            $row['customer_email'] = "lbourrel+" . str_replace("@", "_-at-_", $row['customer_email']) . "@ayaline.com";
        }
        // Suppression des colonnes non utilisées
        unset($row['from_lengow']);
        unset($row['order_id_lengow']);
        unset($row['fees_lengow']);
        unset($row['xml_node_lengow']);
        unset($row['feed_id_lengow']);
        unset($row['message_lengow']);
        unset($row['marketplace_lengow']);
        unset($row['total_paid_lengow']);
        unset($row['carrier_lengow']);
        unset($row['carrier_method_lengow']);

        // suppression de colonnes à mettre à null
        unset($row['quote_address_id']);
        unset($row['quote_id']);

        // Gestion spécifique au store
        if (array_key_exists($row['store_id'], $this->_storeMappingConfigArray)) {
            $row['store_id'] = $this->_storeMappingConfigArray[$row['store_id']];
        }
        // Gestion spécifique au groupe
        if (array_key_exists($row['customer_group_id'], $this->_groupMappingConfigArray)) {
            $row['customer_group_id'] = $this->_groupMappingConfigArray[$row['customer_group_id']];
        }

        // Gestion du statut de l'ERP
        $row['erp_status'] = 'success';

        return $row;
    }

    public function insertGridRecords($row) {
        $ids4grid = array($row["entity_id"]);
        Mage::getResourceModel("sales/order")->updateGridRecords($ids4grid);
    }

    public function transformOrderItemRow($row)
    {
        // suppression de colonnes à mettre à null
        unset($row['quote_item_id']);
        unset($row['product_id']);

        // Gestion spécifique au store
        if (array_key_exists($row['store_id'], $this->_storeMappingConfigArray)) {
            $row['store_id'] = $this->_storeMappingConfigArray[$row['store_id']];
        }

        return $row;
    }

    public function transformAddressRow($row)
    {
        // suppression de colonnes à mettre à null
        unset($row['quote_address_id']);
        return $row;
    }

    public function updateGridRecords($row) {
        $ids4grid = array($row["parent_id"]);
        Mage::getResourceModel("sales/order")->updateGridRecords($ids4grid);
    }

    protected function _process()
    {

        // delete all current orders in new database
        $this->_deleteOrders();

        $this->_processTable(
            "orders",
            "sales_flat_order",
            Mage::getSingleton('core/resource')->getTableName("sales/order"),
            "entity_id",
            array(get_class($this),"transformOrderRow"),
            array(get_class($this),"insertGridRecords"));

        $this->_processTable(
            "order items",
            "sales_flat_order_item",
            Mage::getSingleton('core/resource')->getTableName("sales/order_item"),
            "order_id, item_id",
            array(get_class($this),"transformOrderItemRow"),
            null);

        $this->_processTable(
            "order addresses",
            "sales_flat_order_address",
            Mage::getSingleton('core/resource')->getTableName("sales/order_address"),
            "entity_id",
            array(get_class($this),"transformAddressRow"),
            array(get_class($this),"updateGridRecords"));

        $this->_processTable(
            "order payments",
            "sales_flat_order_payment",
            Mage::getSingleton('core/resource')->getTableName("sales/order_payment"),
            "entity_id",
            array(get_class($this),"transformAddressRow"),
            array(get_class($this),"updateGridRecords"));

        $this->_processTable(
            "order comments",
            "sales_flat_order_status_history",
            Mage::getSingleton('core/resource')->getTableName("sales/order_status_history"),
            "entity_id",
            array(get_class($this),"transformAddressRow"),
            array(get_class($this),"updateGridRecords"));
    }

}