<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 02/02/2016
 * Time: 09:34
 */
class Sabre_Init_Model_Flat_Invoice extends Sabre_Init_Model_Flat
{

    public $actionName = "Import des factures";

    public function insertGridRecords($row) {
        $ids4grid = array($row["entity_id"]);
        Mage::getResourceModel("sales/order_invoice")->updateGridRecords($ids4grid);
    }


    public function transformInvoiceRow($row)
    {
        // Suppression des colonnes non utilisées

        // suppression de colonnes à mettre à null

        // Gestion spécifique au store
        if (array_key_exists($row['store_id'], $this->_storeMappingConfigArray)) {
            $row['store_id'] = $this->_storeMappingConfigArray[$row['store_id']];
        }

        return $row;
    }

    public function transformInvoiceItemRow($row)
    {
        // Suppression des colonnes non utilisées

        // suppression de colonnes à mettre à null
        unset($row["product_id"]);

        return $row;
    }

    protected function _process()
    {

        $this->_deleteInvoices();

        $this->_processTable(
            "invoices",
            "sales_flat_invoice",
            Mage::getSingleton('core/resource')->getTableName("sales/invoice"),
            "entity_id",
            array(get_class($this), "transformInvoiceRow"),
            array(get_class($this), "insertGridRecords"));

        $this->_processTable(
            "invoice items",
            "sales_flat_invoice_item",
            Mage::getSingleton('core/resource')->getTableName("sales/invoice_item"),
            "entity_id",
            array(get_class($this), "transformInvoiceItemRow"),
            null);

        $this->_processTable(
            "invoice comments",
            "sales_flat_invoice_comment",
            Mage::getSingleton('core/resource')->getTableName("sales/invoice_comment"),
            "entity_id",
            null,
            null);


    }


}