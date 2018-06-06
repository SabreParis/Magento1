<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 01/02/2016
 * Time: 15:51
 */
abstract class Sabre_Init_Model_Flat extends Sabre_Init_Model_Abstract
{

    protected $_storeMappingConfigArray = array();
    protected $_groupMappingConfigArray = array();

    /**
     * $entityName : orders
     * $sourceTable : sab_sales_flat_order
     * $destinationTable : sales_flat_order
     * $beforeCallbackFunction : Array("class", "function")
     * $afterCallbackFunction : Array("class", "function")
     *
     * @param $entityName
     * @param $sourceTable
     * @param $destinationTable
     * @param $orderBy
     * @param $beforeCallbackFunction
     * @param $afterCallbackFunction
     */
    protected function _processTable($entityName, $sourceTable, $destinationTable, $orderBy, $beforeCallbackFunction=null, $afterCallbackFunction=null)
    {

        $sourceConnection = $this->getSourceConnection();
        $destinationConnection = $this->getDestinationConnection();
        $sourceTable = $this->_databaseSourcePrefix . $sourceTable;

        $this->_log("Insert $entityName in new database...");
        // Récupération des anciennes entités
        $results = $sourceConnection->query("SELECT * FROM $sourceTable ORDER BY $orderBy");
        $rows = $results->fetchAll();
        $cpt = 0;
        try {
            foreach ($rows as $row) {
                $cpt += 1;

                // Callback avant insertion
                if ($beforeCallbackFunction) {
                    $row = call_user_func($beforeCallbackFunction, $row);
                }

                // insertion
                $destinationConnection->insert($destinationTable, $row);

                // Callback après insertion
                if ($afterCallbackFunction) {
                    call_user_func($afterCallbackFunction, $row);
                }

                if ($cpt % $this->_modulo == 0) {
                    $this->_log("\t... processed $cpt");
                }

            }
        } catch (Exception $e) {
            $this->_log($row);
            $this->_log($e->getMessage());
            exit();
        }
        $this->_log("\t... $cpt done !");
    }

    protected function _init() {
        // mapping des stores
        $mappingConfig = Mage::getConfig()->getNode("init_from_old_prod/mappings/stores");
        $mappingConfigArray = array();
        foreach ($mappingConfig->children() as $__mapping) {
            $src = (string)$__mapping->src;
            $dest = (string)$__mapping->dest;
            $mappingConfigArray[$src] = $dest;
        }
        $this->_storeMappingConfigArray = $mappingConfigArray;

        // mapping des groupes de client
        $mappingConfig = Mage::getConfig()->getNode("init_from_old_prod/mappings/groups");
        $mappingConfigArray = array();
        foreach ($mappingConfig->children() as $__mapping) {
            $src = (string)$__mapping->src;
            $dest = (string)$__mapping->dest;
            $mappingConfigArray[$src] = $dest;
        }
        $this->_groupMappingConfigArray = $mappingConfigArray;

    }

}