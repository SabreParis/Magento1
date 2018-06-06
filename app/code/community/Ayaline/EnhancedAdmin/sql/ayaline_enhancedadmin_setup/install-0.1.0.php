<?php
/**
 * created : 2014
 *
 * @category  Ayaline
 * @package   Ayaline_EnhancedAdmin
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/** @var $this Mage_Core_Model_Resource_Setup */

/**
 * Create table 'ayaline_enhancedadmin/resource'
 */
$this->getConnection()->dropTable($this->getTable('ayaline_enhancedadmin/resource'));
$table = $this->getConnection()
              ->newTable($this->getTable('ayaline_enhancedadmin/resource'))
              ->addColumn(
                  'code',
                  Varien_Db_Ddl_Table::TYPE_TEXT,
                  50,
                  array(
                      'nullable' => false,
                      'primary'  => true,
                  ),
                  'Resource Code'
              )
              ->addColumn(
                  'type',
                  Varien_Db_Ddl_Table::TYPE_TEXT,
                  50,
                  array(
                      'nullable' => false,
                      'primary'  => true,
                  ),
                  'Resource Type' // sql or data
              )
              ->addColumn(
                  'version',
                  Varien_Db_Ddl_Table::TYPE_TEXT,
                  50,
                  array(
                      'nullable' => false,
                      'primary'  => true,
                  ),
                  'Resource Version'
              )
              ->addColumn(
                  'file',
                  Varien_Db_Ddl_Table::TYPE_TEXT,
                  null,
                  array(
                      'nullable' => false,
                  ),
                  'Setup file'
              )
              ->addColumn(
                  'applied',
                  Varien_Db_Ddl_Table::TYPE_SMALLINT,
                  1,
                  array(
                      'nullable' => false,
                      'default'  => 0,
                  ),
                  'Resource Applied'
              )
              ->addColumn(
                  'applied_at',
                  Varien_Db_Ddl_Table::TYPE_DATETIME,
                  null,
                  array(),
                  'Resource Applied datetime'
              )
              ->setComment('Resources');
$this->getConnection()->createTable($table);
