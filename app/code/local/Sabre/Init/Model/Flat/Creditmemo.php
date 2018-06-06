<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 02/02/2016
 * Time: 09:34
 */
class Sabre_Init_Model_Flat_Creditmemo extends Sabre_Init_Model_Flat
{

    public $actionName = "Import des Crédit Memos";

    public function insertGridRecords($row) {
        $ids4grid = array($row["entity_id"]);
        Mage::getResourceModel("sales/order_creditmemo")->updateGridRecords($ids4grid);
    }


    public function transformCreditmemoRow($row)
    {
        // Suppression des colonnes non utilisées

        // suppression de colonnes à mettre à null

        // Gestion spécifique au store
        if (array_key_exists($row['store_id'], $this->_storeMappingConfigArray)) {
            $row['store_id'] = $this->_storeMappingConfigArray[$row['store_id']];
        }

        return $row;
    }

    public function transformCreditmemoItemRow($row)
    {
        // Suppression des colonnes non utilisées

        // suppression de colonnes à mettre à null
        unset($row["product_id"]);

        return $row;
    }

    protected function _process()
    {

        $this->_deleteCreditmemos();

        $this->_processTable(
            "creditmemos",
            "sales_flat_creditmemo",
            Mage::getSingleton('core/resource')->getTableName("sales/creditmemo"),
            "entity_id",
            array(get_class($this), "transformCreditmemoRow"),
            array(get_class($this), "insertGridRecords"));

        $this->_processTable(
            "creditmemo items",
            "sales_flat_creditmemo_item",
            Mage::getSingleton('core/resource')->getTableName("sales/creditmemo_item"),
            "entity_id",
            array(get_class($this), "transformCreditmemoItemRow"),
            null);

        $this->_processTable(
            "creditmemo comments",
            "sales_flat_creditmemo_comment",
            Mage::getSingleton('core/resource')->getTableName("sales/creditmemo_comment"),
            "entity_id",
            null,
            null);

    }

}