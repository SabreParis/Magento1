<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 01/02/2016
 * Time: 09:51
 */
class Sabre_Init_Model_EavEntity_Customer extends Sabre_Init_Model_EavEntity
{

    private $__storeMappingConfigArray = array();
    private $__websiteMappingConfigArray = array();
    private $__groupMappingConfigArray = array();

    protected $_entityName = "customer";
    protected $_entityModelName = "customer/customer";

    public $actionName = "Import des clients";

    protected function _transformRow($row)
    {
        $anonymise = Mage::getStoreConfig("sabre_init/params/anonymise");
        if ($anonymise) {
            $row['email'] = "lbourrel+" . str_replace("@", "_-at-_", $row['email']) . "@ayaline.com";
        }
        // Gestion spécifique au website
        if (array_key_exists($row['website_id'], $this->__websiteMappingConfigArray)) {
            $row['website_id'] = $this->__websiteMappingConfigArray[$row['website_id']];
        }
        // Gestion spécifique au store
        if (array_key_exists($row['store_id'], $this->__storeMappingConfigArray)) {
            $row['store_id'] = $this->__storeMappingConfigArray[$row['store_id']];
        }
        // Gestion spécifique au groupe
        if (array_key_exists($row['group_id'], $this->__groupMappingConfigArray)) {
            $row['group_id'] = $this->__groupMappingConfigArray[$row['group_id']];
        }
        return $row;
    }

    protected function _init() {
        $this->_destEntityTableName = Mage::getSingleton('core/resource')->getTableName("customer/entity");
        $this->_srcEntityTableName = $this->_databaseSourcePrefix . "customer_entity";

        // mapping des websites
        $mappingConfig = Mage::getConfig()->getNode("init_from_old_prod/mappings/websites");
        $mappingConfigArray = array();
        foreach ($mappingConfig->children() as $__mapping) {
            $src = (string)$__mapping->src;
            $dest = (string)$__mapping->dest;
            $mappingConfigArray[$src] = $dest;
        }
        $this->__websiteMappingConfigArray = $mappingConfigArray;

        // mapping des stores
        $mappingConfig = Mage::getConfig()->getNode("init_from_old_prod/mappings/stores");
        $mappingConfigArray = array();
        foreach ($mappingConfig->children() as $__mapping) {
            $src = (string)$__mapping->src;
            $dest = (string)$__mapping->dest;
            $mappingConfigArray[$src] = $dest;
        }
        $this->__storeMappingConfigArray = $mappingConfigArray;

        // mapping des groupes de client
        $mappingConfig = Mage::getConfig()->getNode("init_from_old_prod/mappings/groups");
        $mappingConfigArray = array();
        foreach ($mappingConfig->children() as $__mapping) {
            $src = (string)$__mapping->src;
            $dest = (string)$__mapping->dest;
            $mappingConfigArray[$src] = $dest;
        }
        $this->__groupMappingConfigArray = $mappingConfigArray;

    }


}