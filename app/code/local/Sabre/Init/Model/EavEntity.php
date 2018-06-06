<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 01/02/2016
 * Time: 09:51
 */
abstract class Sabre_Init_Model_EavEntity extends Sabre_Init_Model_Abstract
{

    protected $_entityName = "";
    protected $_entityModelName = "";
    protected $_srcEntityTableName = "";
    protected $_destEntityTableName = "";

    abstract protected function _transformRow($row);

    protected function _process() {

        $sourceConnection = $this->getSourceConnection();
        $destinationConnection = $this->getDestinationConnection();

        // delete all current entities in new database
        $this->_deleteEntities($this->_entityName, $this->_entityModelName);

        $this->_log( "Insert {$this->_entityName} entities in new database..." );
        // Récupération des anciennes entités
        $results    = $sourceConnection->query('SELECT * FROM '. $this->_srcEntityTableName .' ORDER BY entity_id');
        $rows = $results->fetchAll();
        $cpt = 0;
        try {
            foreach($rows as $row) {
                $cpt+=1;
                $newRow = $this->_transformRow($row);
                // insertion
                $destinationConnection->insert($this->_destEntityTableName, $newRow);
                if ($cpt % $this->_modulo == 0) {
                    $this->_log( "\t... processed $cpt" );
                }
            }
        } catch (Exception $e) {
            $this->_log($row);
            $this->_log($e->getMessage());
            exit();
        }
        $this->_log( "\t... $cpt done !" );

        // gestion des attributs datetime
        $listOfAttributeTypes = array("datetime", "decimal", "int", "text", "varchar");
        foreach ($listOfAttributeTypes as $_attributeType) {
            $this->_log( "Insert {$this->_entityName} entities attributes of type '$_attributeType' in new database..." );
            $results = $sourceConnection->query("SELECT * FROM {$this->_srcEntityTableName}_$_attributeType ORDER BY entity_id");
            $rows = $results->fetchAll();
            $cpt = 0;
            try {
                foreach($rows as $row) {
                    $cpt+=1;
                    $destinationConnection->insert("{$this->_destEntityTableName}_$_attributeType", $row);
                    if ($cpt % $this->_modulo == 0) {
                        $this->_log( "\t... processed $cpt" );
                    }
                }
            } catch (Exception $e) {
                $this->_log($e->getMessage());
                $this->_log($row);
                exit();
            }
            $this->_log( "\t... $cpt done !" );
        }

    }

}