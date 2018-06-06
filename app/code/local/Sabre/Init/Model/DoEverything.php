<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 02/02/2016
 * Time: 17:30
 */
class Sabre_Init_Model_DoEverything extends Sabre_Init_Model_Abstract
{

    protected function _process()
    {

        // Delete everything
        $destinationConnection = $this->getDestinationConnection();
        $this->_deleteShipments();
        $this->_deleteCreditmemos();
        $this->_deleteInvoices();
        $this->_deleteOrders();
        $this->_deleteEntities("customer addresses", "customer/address");
        $this->_deleteEntities("customers", "customer/customer");

        // Importation des données
        $this->_log( "-----------------------" );
        $this->_log( "Importation des Données" );
        $this->_log( "-----------------------" );
        Mage::getModel("sabre_init/eavEntity_customer")->main();
        Mage::getModel("sabre_init/eavEntity_customerAddress")->main();
        Mage::getModel("sabre_init/flat_order")->main();
        Mage::getModel("sabre_init/flat_invoice")->main();
        Mage::getModel("sabre_init/flat_shipment")->main();
        Mage::getModel("sabre_init/flat_creditmemo")->main();



    }

    protected function _init()
    {
    }
}