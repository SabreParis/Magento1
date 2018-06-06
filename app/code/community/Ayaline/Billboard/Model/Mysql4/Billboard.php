<?php
/**
 * created : 10/08/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 *
 * @package Ayaline_Billboard
 */
class Ayaline_Billboard_Model_Mysql4_Billboard extends Mage_Core_Model_Mysql4_Abstract
{

    protected $_storeTable;
    protected $_categoryTable;
    protected $_productTable;
    protected $_billboardTypeTable;
    protected $_customerGroupTable;

    protected function _construct()
    {
        $this->_init('ayalinebillboard/billboard', 'billboard_id');
        $this->_storeTable = $this->getTable('ayalinebillboard/billboard_store');
        $this->_categoryTable = $this->getTable('ayalinebillboard/billboard_category');
        $this->_productTable = $this->getTable('ayalinebillboard/billboard_product');
        $this->_billboardTypeTable = $this->getTable('ayalinebillboard/billboard_type_index');
        $this->_customerGroupTable = $this->getTable('ayalinebillboard/billboard_customer_group');
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $this->loadStores($object);
        $this->loadTypes($object);
        $this->loadCustomerGroups($object);

        return parent::_afterLoad($object);
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!Mage::helper('ayalinecore')->isValidCode($object->getIdentifier())) {
            Mage::throwException(Mage::helper('ayalinebillboard')->__('Please enter a valid identifier. For example something_1, block5, id-4.'));
        }

        if (!$this->_isUniqueIdentifierByStores($object)) {
            Mage::throwException(Mage::helper('ayalinebillboard')->__('The <em>identifier</em> must be unique for each stores'));
        }

        $zd = Mage::app()->getLocale()->date();
        if (!$object->getId()) {
            $object->setCreatedAt($zd->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
        }
        $object->setUpdatedAt($zd->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));

        return parent::_beforeSave($object);
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $this->_saveStores($object);
        $this->_saveTypes($object);
        $this->_saveCustomerGroups($object);

        return parent::_afterSave($object);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param Mage_Core_Model_Abstract $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $stores = array(
                (int) $object->getStoreId(),
                Mage_Core_Model_App::ADMIN_STORE_ID,
            );

            $select->join(
                array('bs' => $this->_storeTable),
                "{$this->getMainTable()}.{$this->getIdFieldName()} = bs.{$this->getIdFieldName()}",
                array('store_id')
            )
                ->where('is_active = ?', 1)
                ->where('bs.store_id in (?) ', $stores)
                ->order('store_id DESC')
                ->limit(1);
        }

        return $select;
    }

    /**
     * Check if billboard identifier is unique for selected stores
     *
     * @param Ayaline_Billboard_Model_Billboard $object
     * @return bool (true if is unique)
     */
    protected function _isUniqueIdentifierByStores($object)
    {
        $sql = $this->_getReadAdapter()->select()
                    ->from(array('main_table' => $this->getMainTable()))
                    ->joinInner(
                        array('abbs' => $this->_storeTable),
                        'main_table.' . $this->getIdFieldName() . ' = abbs.' . $this->getIdFieldName(),
                        array()
                    )
                    ->where('main_table.identifier = ?', $object->getIdentifier());

        if ($object->getId()) {
            $sql->where('main_table.' . $this->getIdFieldName() . ' <> ?', $object->getId());
        }

        $stores = (array)$object->getData('stores');
        if (Mage::app()->isSingleStoreMode()) {
            $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID);
        }
        $sql->where('abbs.store_id IN (?)', $stores);

        return !(bool)$this->_getReadAdapter()->fetchOne($sql);
    }

    /**
     * Saving association with stores
     *
     * @param Ayaline_Billboard_Model_Billboard $object
     * @return Ayaline_Billboard_Model_Mysql4_Billboard
     */
    protected function _saveStores($object)
    {
        $where = $this->_getWriteAdapter()->quoteInto($this->getIdFieldName() . ' = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->_storeTable, $where);

        foreach ((array)$object->getData('stores') as $_storeId) {
            $bind = array($this->getIdFieldName() => $object->getId(), 'store_id' => $_storeId);
            $this->_getWriteAdapter()->insert($this->_storeTable, $bind);
        }

        return $this;
    }

    /**
     * Saving association with billboard types
     *
     * @param Ayaline_Billboard_Model_Billboard $object
     * @return Ayaline_Billboard_Model_Mysql4_Billboard
     */
    protected function _saveTypes($object)
    {
        $where = $this->_getWriteAdapter()->quoteInto($this->getIdFieldName() . ' = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->_billboardTypeTable, $where);

        foreach ((array)$object->getData('types') as $_typeId) {
            $bind = array($this->getIdFieldName() => $object->getId(), 'type_id' => $_typeId);
            $this->_getWriteAdapter()->insert($this->_billboardTypeTable, $bind);
        }

        return $this;
    }

    /**
     * Saving association with customer group
     *
     * @param Ayaline_Billboard_Model_Billboard $object
     * @return Ayaline_Billboard_Model_Mysql4_Billboard
     */
    protected function _saveCustomerGroups($object)
    {
        $where = $this->_getWriteAdapter()->quoteInto($this->getIdFieldName() . ' = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->_customerGroupTable, $where);

        foreach ((array)$object->getData('customer_group_ids') as $_customerGroupId) {
            $bind = array($this->getIdFieldName() => $object->getId(), 'customer_group_id' => $_customerGroupId);
            $this->_getWriteAdapter()->insert($this->_customerGroupTable, $bind);
        }

        return $this;
    }

    /**
     * Load stores assocition
     *
     * @param Ayaline_Billboard_Model_Billboard $object
     * @return Ayaline_Billboard_Model_Mysql4_Billboard
     */
    public function loadStores($object)
    {
        $selectStore = $this->_getReadAdapter()->select()->from($this->_storeTable)->where($this->getIdFieldName() . ' = ?', $object->getId());
        if ($data = $this->_getReadAdapter()->fetchAll($selectStore)) {
            $storesArray = array();
            foreach ($data as $row) {
                $storesArray[] = $row['store_id'];
            }
            $object->setData('store_id', $storesArray);
        }

        return $this;
    }

    /**
     * Load billboard types association
     *
     * @param Ayaline_Billboard_Model_Billboard $object
     * @return Ayaline_Billboard_Model_Mysql4_Billboard
     */
    public function loadTypes($object)
    {
        $selectType = $this->_getReadAdapter()->select()->from($this->_billboardTypeTable)->where($this->getIdFieldName() . ' = ?', $object->getId());
        if ($data = $this->_getReadAdapter()->fetchAll($selectType)) {
            $typesArray = array();
            foreach ($data as $row) {
                $typesArray[] = $row['type_id'];
            }
            $object->setData('type_id', $typesArray);
        }

        return $this;
    }

    /**
     * Load customer groups association
     *
     * @param Ayaline_Billboard_Model_Billboard $object
     * @return Ayaline_Billboard_Model_Mysql4_Billboard
     */
    public function loadCustomerGroups($object)
    {
        $selectCustomerGroups = $this->_getReadAdapter()->select()->from($this->_customerGroupTable)->where($this->getIdFieldName() . ' = ?', $object->getId());
        if ($data = $this->_getReadAdapter()->fetchAll($selectCustomerGroups)) {
            $customerGroups = array();
            foreach ($data as $_row) {
                $customerGroups[] = $_row['customer_group_id'];
            }
            $object->setData('customer_group_id', $customerGroups);
        }

        return $this;
    }

    /**
     * Retrieve billboard product position
     *
     * @param Mage_Catalog_Model_Product $product
     * @return array
     */
    public function getBillboardProductPosition($product)
    {
        return $this->_getBillboardPosition($product, $this->_productTable, 'product_id');
    }

    /**
     * Retrieve billboard category position
     *
     * @param Mage_Catalog_Model_Category $category
     * @return array
     */
    public function getBillboardCategoryPosition($category)
    {
        return $this->_getBillboardPosition($category, $this->_categoryTable, 'category_id');
    }

    /**
     * Load billboard ids to category
     *
     * @param Mage_Catalog_Model_Category $category
     * @return Ayaline_Billboard_Model_Mysql4_Billboard
     */
    public function loadBillboardToCategory($category)
    {
        $this->_loadBillboardTo($category, $this->_categoryTable, 'category_id');

        return $this;
    }

    /**
     * Load billboard ids to product
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Ayaline_Billboard_Model_Mysql4_Billboard
     */
    public function loadBillboardToProduct($product)
    {
        $this->_loadBillboardTo($product, $this->_productTable, 'product_id');

        return $this;
    }

    /**
     * Save billboard - position / category association
     *
     * @param Mage_Catalog_Model_Category $category
     * @return Ayaline_Billboard_Model_Mysql4_Billboard
     */
    public function saveBillboardToCategory($category)
    {
        $this->_saveBillboardTo($category, $this->_categoryTable, 'category_id');

        return $this;
    }

    /**
     * Save billboard - position / product association
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Ayaline_Billboard_Model_Mysql4_Billboard
     */
    public function saveBillboardToProduct($product)
    {
        $this->_saveBillboardTo($product, $this->_productTable, 'product_id');

        return $this;
    }

    /**
     * Retrieve billboard position
     *
     * @param Mage_Catalog_Model_Category|Mage_Catalog_Model_Product $object
     * @param string                                                 $table
     * @param string                                                 $field
     * @return array
     */
    protected function _getBillboardPosition($object, $table, $field)
    {
        $sql = $this->_getReadAdapter()->select()
                    ->from($table, array($this->getIdFieldName(), 'position'))
                    ->where($field . ' = ?', $object->getId());
        $positions = $this->_getReadAdapter()->fetchPairs($sql);

        return $positions;
    }

    /**
     * Save bllboard - position / object association
     *
     * @param Mage_Catalog_Model_Category|Mage_Catalog_Model_Product $object
     * @param string                                                 $table
     * @param string                                                 $field
     * @return Ayaline_Billboard_Model_Mysql4_Billboard
     */
    protected function _saveBillboardTo($object, $table, $field)
    {
        $billboards = $object->getBillboards();
        if (is_array($billboards)) {
            $condition = $this->_getWriteAdapter()->quoteInto($field . ' = ?', $object->getId());
            $this->_getWriteAdapter()->delete($table, $condition);
            if (is_array($billboards) && !empty($billboards)) {
                foreach ($billboards as $_billboardId => $_position) {
                    $row = array(
                        $this->getIdFieldName() => $_billboardId,
                        $field                  => $object->getId(),
                        'position'              => $_position
                    );
                    $this->_getWriteAdapter()->insert($table, $row);
                }
            }
        }

        return $this;
    }

    /**
     * Load billboard ids to object
     *
     * @param Mage_Catalog_Model_Category|Mage_Catalog_Model_Product $object
     * @param string                                                 $table
     * @param string                                                 $field
     * @return Ayaline_Billboard_Model_Mysql4_Billboard
     */
    protected function _loadBillboardTo($object, $table, $field)
    {
        $sql = $this->_getReadAdapter()->select()
                    ->from($table, $this->getIdFieldName())
                    ->where($field . ' = ?', $object->getId());
        $billboardIds = $this->_getReadAdapter()->fetchCol($sql);
        $object->setBillboardIds($billboardIds);

        return $this;
    }

}
