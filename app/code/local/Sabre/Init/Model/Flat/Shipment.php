<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 02/02/2016
 * Time: 09:34
 */
class Sabre_Init_Model_Flat_Shipment extends Sabre_Init_Model_Flat
{

    public $actionName = "Import des bons de livraison";

    public function insertGridRecords($row) {
        $ids4grid = array($row["entity_id"]);
        Mage::getResourceModel("sales/order_shipment")->updateGridRecords($ids4grid);
    }


    public function transformShipmentRow($row)
    {
        // Suppression des colonnes non utilisées

        // suppression de colonnes à mettre à null

        // Gestion spécifique au store
        if (array_key_exists($row['store_id'], $this->_storeMappingConfigArray)) {
            $row['store_id'] = $this->_storeMappingConfigArray[$row['store_id']];
        }

        return $row;
    }

    public function transformShipmentItemRow($row)
    {
        // Suppression des colonnes non utilisées

        // suppression de colonnes à mettre à null
        unset($row["product_id"]);

        return $row;
    }

    protected function _process()
    {

        $this->_deleteShipments();

        $this->_processTable(
            "shipments",
            "sales_flat_shipment",
            Mage::getSingleton('core/resource')->getTableName("sales/shipment"),
            "entity_id",
            array(get_class($this), "transformShipmentRow"),
            array(get_class($this), "insertGridRecords"));

        $this->_processTable(
            "shipment items",
            "sales_flat_shipment_item",
            Mage::getSingleton('core/resource')->getTableName("sales/shipment_item"),
            "entity_id",
            array(get_class($this), "transformShipmentItemRow"),
            null);

        $this->_processTable(
            "shipment comments",
            "sales_flat_shipment_comment",
            Mage::getSingleton('core/resource')->getTableName("sales/shipment_comment"),
            "entity_id",
            null,
            null);

        $this->_processTable(
            "trackings",
            "sales_flat_shipment_track",
            Mage::getSingleton('core/resource')->getTableName("sales/shipment_track"),
            "entity_id",
            null,
            null);


    }


}