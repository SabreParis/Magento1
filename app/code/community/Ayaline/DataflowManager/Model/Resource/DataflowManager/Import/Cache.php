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
class Ayaline_DataflowManager_Model_Resource_DataflowManager_Import_Cache extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('ayaline_dataflowmanager/import_cache', null);
        $this->_isPkAutoIncrement = false;
    }

    /**
     * @param int    $objectId
     * @param string $objectType
     * @return array|false
     */
    public function getCache($objectId, $objectType)
    {
        $sql = $this->_getWriteAdapter()
                    ->select()
                    ->from($this->getMainTable())
                    ->where('object_id = ?', $objectId)
                    ->where('object_type = ?', $objectType);

        return $this->_getWriteAdapter()->fetchRow($sql, array(), Zend_Db::FETCH_ASSOC);
    }

    /**
     * @param int    $objectId
     * @param string $objectType
     * @param string $objectHash
     * @return int
     */
    public function updateCache($objectId, $objectType, $objectHash)
    {
        $data = array(
            'object_id'   => $objectId,
            'object_type' => $objectType,
            'object_hash' => $objectHash,
        );

        $fields = array('object_hash' => new Zend_Db_Expr($this->_getWriteAdapter()->quote($objectHash)));

        return $this->_getWriteAdapter()->insertOnDuplicate(
            $this->getMainTable(),
            $data,
            $fields
        );
    }

    /**
     * @param int    $objectId
     * @param string $objectType
     * @param string $objectHashToCheck
     * @return bool
     */
    public function checkObjectHashExists($objectId, $objectType, $objectHashToCheck)
    {
        $row = $this->getCache($objectId, $objectType);
        if ($row !== false) {
            if ($row['object_hash'] === $objectHashToCheck) {
                return true;
            }
        }

        return false;
    }

}