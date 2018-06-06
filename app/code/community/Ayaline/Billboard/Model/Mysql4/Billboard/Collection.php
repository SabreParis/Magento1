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
class Ayaline_Billboard_Model_Mysql4_Billboard_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    protected $_eventPrefix = 'ayalinebillboard_billboard_collection';
    protected $_eventObject = 'billboard_collection';

    protected $_storeTable;
    protected $_categoryTable;
    protected $_productTable;
    protected $_typeTable;
    protected $_typeIndexTable;
    protected $_customerGroupTable;

    protected $_isGrouped = false;

    protected function _construct()
    {
        $this->_init('ayalinebillboard/billboard');
        $this->_storeTable = $this->getTable('ayalinebillboard/billboard_store');
        $this->_categoryTable = $this->getTable('ayalinebillboard/billboard_category');
        $this->_productTable = $this->getTable('ayalinebillboard/billboard_product');
        $this->_typeTable = $this->getTable('ayalinebillboard/billboard_type');
        $this->_typeIndexTable = $this->getTable('ayalinebillboard/billboard_type_index');
        $this->_customerGroupTable = $this->getTable('ayalinebillboard/billboard_customer_group');
    }

    /**
     * Add filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @param bool                      $withAdmin
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array($store->getId());
        }

        $this->getSelect()
             ->joinInner(
                 array('abbs' => $this->_storeTable),
                 'main_table.billboard_id = abbs.billboard_id',
                 array()
             )
             ->where('abbs.store_id IN (?)', ($withAdmin ? array(
                     Mage_Core_Model_App::ADMIN_STORE_ID,
                     $store
                 ) : $store));

        if (!$this->_isGrouped) {
            $this->getSelect()->group('main_table.billboard_id');
            $this->_isGrouped = true;
        }

        return $this;
    }

    /**
     * Add billboard type filter
     *
     * @param array(int)|Ayaline_Billboard_Model_Billboard_Type $type
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function addBillboardTypeFilter($type)
    {
        if ($type instanceof Ayaline_Billboard_Model_Billboard_Type) {
            $type = $type->getId();
        }
        if (!is_array($type)) {
            $type = array($type);
        }

        $this->getSelect()
             ->joinInner(
                 array('abbti' => $this->_typeIndexTable),
                 'main_table.billboard_id = abbti.billboard_id',
                 array()
             )
             ->where('abbti.type_id IN (?)', $type);

        if (!$this->_isGrouped) {
            $this->getSelect()->group('main_table.billboard_id');
            $this->_isGrouped = true;
        }

        return $this;
    }

    /**
     * Filter billboards by category and order by position
     *
     * @param int|Mage_Catalog_Model_Category $category
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function addCategoryFilter($category = null)
    {
        if (is_null($category)) {
            $category = Mage::app()->getStore()->getRootCategoryId();
        }
        if ($category instanceof Mage_Catalog_Model_Category) {
            $category = $category->getId();
        }
        $this->addObjectFilter($category, 'category_id', $this->_categoryTable, 'abbc');

        return $this;
    }

    /**
     * Filter billboards by product and order by position
     *
     * @param int|Mage_Catalog_Model_Product $product
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function addProductFilter($product)
    {
        if ($product instanceof Mage_Catalog_Model_Product) {
            $product = $product->getId();
        }
        $this->addObjectFilter($product, 'product_id', $this->_productTable, 'abbp');

        return $this;
    }

    /**
     * Add addtionnal filter on billboard collection
     *
     * @param int|string $value
     * @param string     $field
     * @param string     $table
     * @param string     $tableAlias
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function addObjectFilter($value, $field, $table, $tableAlias)
    {
        $this->getSelect()
             ->joinInner(
                 array($tableAlias => $table),
                 "main_table.billboard_id = {$tableAlias}.billboard_id",
                 array("{$tableAlias}.position" => 'position')
             )
             ->where("{$tableAlias}.{$field} = ?", $value)
             ->order('position ASC');

        return $this;
    }

    /**
     * Filter billboards by status (0 or 1)
     *
     * @param int $status
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function addStatusFilter($status = 1)
    {
        $this->getSelect()->where('main_table.is_active = ?', $status);

        return $this;
    }

    /**
     * Filter billboards by date time interval (by default now)
     *
     * @param Zend_Date $zd
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function addDateTimeFilter($zd = null)
    {
        if (is_null($zd)) {
            $zd = Mage::app()->getLocale()->date();
        }
        $this->getSelect()->where('? BETWEEN main_table.display_from AND main_table.display_to', $zd->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));

        return $this;
    }

    /**
     * Filter billboards by identifiers
     *
     * @param array|string $identifiers
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function addIdentifierFilter($identifiers)
    {
        if (!is_array($identifiers)) {
            $identifiers = array($identifiers);
        }
        $this->getSelect()->where('main_table.identifier IN (?)', $identifiers);

        return $this;
    }

    /**
     * Filter billboards by billboard type identifier
     *
     * @param string $typeIdentifier
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function addBillboardTypeIdentifierFilter($typeIdentifier)
    {
        $this->getSelect()
             ->joinInner(
                 array('abbti' => $this->_typeIndexTable),
                 'main_table.billboard_id = abbti.billboard_id',
                 array()
             )
             ->joinInner(
                 array('abbt' => $this->_typeTable),
                 'abbti.type_id = abbt.type_id',
                 array()
             )
             ->where('abbt.identifier = ?', $typeIdentifier);

        if (!$this->_isGrouped) {
            $this->getSelect()->group('main_table.billboard_id');
            $this->_isGrouped = true;
        }

        return $this;
    }

    /**
     * Filter billboards by customer groups
     *
     * @param array(int)|Mage_Customer_Model_Group $customerGroup
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function addCustomerGroupFilter($customerGroup)
    {
        if ($customerGroup instanceof Mage_Customer_Model_Group) {
            $customerGroup = $customerGroup->getId();
        }
        if (!is_array($customerGroup)) {
            $customerGroup = array($customerGroup);
        }

        $this->getSelect()
             ->joinInner(
                 array('abbcg' => $this->_customerGroupTable),
                 'main_table.billboard_id = abbcg.billboard_id',
                 array()
             )
             ->where('abbcg.customer_group_id IN (?)', $customerGroup);

        if (!$this->_isGrouped) {
            $this->getSelect()->group('main_table.billboard_id');
            $this->_isGrouped = true;
        }

        return $this;
    }

    /**
     * Order by widget position
     *
     * @param string $direction
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function orderByWidgetPosition($direction = Varien_Data_Collection::SORT_ORDER_ASC)
    {
        return $this->addOrder('widget_position', $direction);
    }

}