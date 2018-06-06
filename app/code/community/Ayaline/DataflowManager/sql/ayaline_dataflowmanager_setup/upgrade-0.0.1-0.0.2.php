<?php
/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/** @var $this Mage_Core_Model_Resource_Setup */

$this->startSetup();

$this->getConnection()->dropTable($this->getTable('ayaline_dataflowmanager/import_cache'));
$table = $this->getConnection()
              ->newTable($this->getTable('ayaline_dataflowmanager/import_cache'))
              ->addColumn(
                  'object_id',
                  Varien_Db_Ddl_Table::TYPE_INTEGER,
                  null,
                  array(
                      'unsigned' => true,
                      'primary'  => true,
                      'nullable' => false,
                      'comment'  => 'Object Id',
                  )
              )
              ->addColumn(
                  'object_type',
                  Varien_Db_Ddl_Table::TYPE_VARCHAR,
                  255,
                  array(
                      'primary'  => true,
                      'nullable' => false,
                      'comment'  => 'Object Type',
                  )
              )
              ->addColumn(
                  'object_hash',
                  Varien_Db_Ddl_Table::TYPE_BLOB,
                  '64k',
                  array(
                      'comment' => 'Object Hash',
                  )
              )
              ->addColumn(
                  'updated_at',
                  Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                  null,
                  array(
                      'nullable' => false,
                      'default'  => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE,
                      'comment'  => 'Last cache update',
                  )
              )
              ->setComment('aYaline Dataflow Manager: Import cache table');
$this->getConnection()->createTable($table);

$this->endSetup();
