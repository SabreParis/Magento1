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
class Sabre_Billboard_Model_Resource_Billboard extends Ayaline_Billboard_Model_Mysql4_Billboard
{

    /**
     * Check if billboard identifier exist for specific store and is a landing page
     * return billboard id if page exists
     *
     * @param string $identifier
     * @param int    $storeId
     * @return int
     */
    public function checkLandingPageIdentifier($identifier, $storeId)
    {
        $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeId);
        $select = $this->_getReadAdapter()->select()
                       ->from(['main_table' => $this->getMainTable()], $this->getIdFieldName())
                       ->joinInner(
                           ['store_table' => $this->_storeTable],
                           "main_table.{$this->getIdFieldName()} = store_table.{$this->getIdFieldName()}",
                           []
                       )
                       ->joinInner(
                           ['type_idx_table' => $this->_billboardTypeTable],
                           "main_table.{$this->getIdFieldName()} = type_idx_table.{$this->getIdFieldName()}",
                           []
                       )
                       ->joinInner(
                           ['type_table' => $this->getTable('ayalinebillboard/billboard_type')],
                           "type_idx_table.type_id = type_table.type_id",
                           []
                       )
                       ->where('main_table.identifier = ?', $identifier)
                       ->where('store_table.store_id IN (?)', $stores)
                       ->where('type_table.identifier = ?', Sabre_Billboard_Model_Billboard::LANDING_PAGE_IDENTIFIER)
                       ->where('main_table.is_active = ?', 1)
                       ->order('store_table.store_id DESC')
                       ->limit(1);

        return $this->_getReadAdapter()->fetchOne($select);
    }

}
