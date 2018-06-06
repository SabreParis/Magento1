<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 01/02/2016
 * Time: 15:51
 */
abstract class Sabre_Init_Model_Abstract
{

    /* @var $__sourceConnection Varien_Db_Adapter_Interface  */
    private $__sourceConnection;
    /* @var $__destinationConnection Varien_Db_Adapter_Interface  */
    private $__destinationConnection;

    protected $_modulo = 1000;
    protected $_databaseSourcePrefix = "";

    public $logfile = "init_entities.log";

    abstract protected function _process();

    abstract protected function _init();

    public $actionName = "";

    private function __init() {

        if (!Mage::getStoreConfigFlag("sabre_init/params/enabled")) {
            $this->_log(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>");
            $this->_log("Module disabled");
            $this->_log(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>");
            exit();
        }

        $dbResource = Mage::getSingleton('core/resource');

        Mage::getConfig()->setNode("global/resources/source_init/connection/host", Mage::getStoreConfig("sabre_init/source_database/host"), true);
        Mage::getConfig()->setNode("global/resources/source_init/connection/username", Mage::getStoreConfig("sabre_init/source_database/username"), true);
        Mage::getConfig()->setNode("global/resources/source_init/connection/password", Mage::getStoreConfig("sabre_init/source_database/password"), true);
        Mage::getConfig()->setNode("global/resources/source_init/connection/dbname", Mage::getStoreConfig("sabre_init/source_database/dbname"), true);

        $this->__sourceConnection = $dbResource->getConnection('source_init');
        $this->__destinationConnection = $dbResource->getConnection('core_write');

        $this->_databaseSourcePrefix = Mage::getStoreConfig("sabre_init/source_database/prefix");

    }

    protected function _log($message) {
        Mage::log($message, null, $this->logfile, true);
        print_r($message);print_r("\n");
    }

    public function main() {
        $this->__init();
        $this->_log(str_repeat("-", strlen($this->actionName)));
        $this->_log( $this->actionName );
        $this->_log(str_repeat("-", strlen($this->actionName)));
        $this->_init();
        $this->_process();
    }

    /**
     * @return Varien_Db_Adapter_Interface
     */
    public function getSourceConnection() {
        return $this->__sourceConnection;
    }

    /**
     * @return Varien_Db_Adapter_Interface
     */
    public function getDestinationConnection() {
        return $this->__destinationConnection;
    }

    protected function _deleteEntities($entityName, $entityModelName) {
        $this->_log( "Delete all current {$entityName} in new database..." );
        $entities = Mage::getModel($entityModelName)->getCollection();
        $entities->delete();
        $this->_log( "\t... done !" );
    }

    protected function _deleteOrders() {
        // delete all current orders in new database
        $this->_log( "Delete all current orders in new database..." );
        $tables = array(
            Mage::getSingleton('core/resource')->getTableName("sales/order"),
            Mage::getSingleton('core/resource')->getTableName("sales/order_grid"),
            Mage::getSingleton('core/resource')->getTableName("sales/order_item"),
            Mage::getSingleton('core/resource')->getTableName("sales/order_address"),
            Mage::getSingleton('core/resource')->getTableName("sales/order_status_history"),
            Mage::getSingleton('core/resource')->getTableName("sales/order_payment")
        );
        foreach ($tables as $table) {
            $this->__destinationConnection->query("DELETE FROM $table");
        }
        $this->_log( "\t... done !" );
    }

    protected function _deleteInvoices() {
        $this->_log( "Delete all current invoices in new database..." );
        $tables = array(
            Mage::getSingleton('core/resource')->getTableName("sales/invoice"),
            Mage::getSingleton('core/resource')->getTableName("sales/invoice_comment"),
            Mage::getSingleton('core/resource')->getTableName("sales/invoice_grid"),
            Mage::getSingleton('core/resource')->getTableName("sales/invoice_item"),
        );
        foreach ($tables as $table) {
            $this->__destinationConnection->query("DELETE FROM $table");
        }
        $this->_log( "\t... done !" );
    }

    protected function _deleteCreditmemos() {
        $this->_log( "Delete all current creditmemos in new database..." );
        $tables = array(
            Mage::getSingleton('core/resource')->getTableName("sales/creditmemo"),
            Mage::getSingleton('core/resource')->getTableName("sales/creditmemo_comment"),
            Mage::getSingleton('core/resource')->getTableName("sales/creditmemo_grid"),
            Mage::getSingleton('core/resource')->getTableName("sales/creditmemo_item"),
        );
        foreach ($tables as $table) {
            $this->__destinationConnection->query("DELETE FROM $table");
        }
        $this->_log( "\t... done !" );
    }

    protected function _deleteShipments() {
        $this->_log( "Delete all current shipments in new database..." );
        $tables = array(
            Mage::getSingleton('core/resource')->getTableName("sales/shipment"),
            Mage::getSingleton('core/resource')->getTableName("sales/shipment_comment"),
            Mage::getSingleton('core/resource')->getTableName("sales/shipment_grid"),
            Mage::getSingleton('core/resource')->getTableName("sales/shipment_item"),
            Mage::getSingleton('core/resource')->getTableName("sales/shipment_track"),
        );
        foreach ($tables as $table) {
            $this->__destinationConnection->query("DELETE FROM $table");
        }
        $this->_log("\t... done !");
    }



}