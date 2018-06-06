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
class Ayaline_Billboard_Block_Adminhtml_Catalog_Category_Tab_Billboard extends Mage_Adminhtml_Block_Widget_Grid implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('ayalinebillboard_catalog_category_billboard');
        $this->setDefaultSort('billboard_id');
        $this->setUseAjax(true);
    }

    public function getCategory()
    {
        return Mage::registry('category');
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'billboard_in_category') {
            $billboardIds = $this->_getSelectedBillboards();
            if (empty($billboardIds)) {
                $billboardIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('main_table.billboard_id', array('in' => $billboardIds));
            } elseif (!empty($billboardIds)) {
                $this->getCollection()->addFieldToFilter('main_table.billboard_id', array('nin' => $billboardIds));
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    protected function _prepareCollection()
    {
        if ($this->getCategory()->getId()) {
            $this->setDefaultFilter(array('billboard_in_category' => 1));
        }

        /* @var $collection Ayaline_Billboard_Model_Mysql4_Billboard_Collection */
        $collection = Mage::getResourceModel('ayalinebillboard/billboard_collection');
        //$collection->addFieldToFilter('is_active', array('eq' => 1));	//	we can filter with the column

        $collection->getSelect()->joinLeft(
            array('abbc' => $collection->getTable('ayalinebillboard/billboard_category')),
            "main_table.billboard_id = abbc.billboard_id AND abbc.category_id = '" . (int)$this->getRequest()->getParam('id', 0) . "'",
            array('position' => 'abbc.position')
        );

        if ($store = $this->getRequest()->getParam('store')) {
            $collection->addStoreFilter($store, true);
        }

        $collection->addOrder('is_active', 'DESC');

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        Mage::helper('ayalinebillboard')->addBillboardTypeRendrerAndFilter($this);

        $this->addColumn('billboard_in_category', array(
            'header_css_class' => 'a-center',
            'type'             => 'checkbox',
            'name'             => 'billboard_in_category',
            'values'           => $this->_getSelectedBillboards(),
            'align'            => 'center',
            'index'            => 'billboard_id',
        ));

        $this->addColumn('billboard_id', array(
            'header'                    => Mage::helper('ayalinebillboard')->__('ID'),
            'sortable'                  => true,
            'width'                     => '60',
            'index'                     => 'billboard_id',
            'filter_condition_callback' => array($this, '_filterBillboardIdCondition'),
        ));

        $this->addColumn('identifier', array(
            'header' => Mage::helper('ayalinebillboard')->__('Identifier'),
            'index'  => 'identifier'
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('ayalinebillboard')->__('Title'),
            'index'  => 'title'
        ));

        $this->addColumn('display_from', array(
            'header' => Mage::helper('ayalinebillboard')->__('Display from'),
            'index'  => 'display_from',
            'type'   => 'datetime',
        ));

        $this->addColumn('display_to', array(
            'header' => Mage::helper('ayalinebillboard')->__('Display to'),
            'index'  => 'display_to',
            'type'   => 'datetime',
        ));

        $this->addColumn('type_id', array(
            'header'                    => Mage::helper('ayalinebillboard')->__('Type'),
            'index'                     => 'type_id',
            'type'                      => 'billboard_type',
            'sortable'                  => false,
            'filter_condition_callback' => array($this, '_filterBillboardTypeCondition'),
        ));

        $this->addColumn('customer_group_id', array(
            'header'                    => Mage::helper('ayalinebillboard')->__('Customer Groups'),
            'index'                     => 'customer_group_id',
            'type'                      => 'options',
            'sortable'                  => false,
            'filter_condition_callback' => array($this, '_filterCustomerGroupCondition'),
            'options'                   => Mage::getSingleton('ayalinebillboard/system_source_customerGroup')->toOptionHash(),
        ));

        $this->addColumn('is_active', array(
            'header'  => Mage::helper('ayalinebillboard')->__('Status'),
            'index'   => 'is_active',
            'width'   => '50',
            'type'    => 'options',
            'options' => array(
                1 => Mage::helper('adminhtml')->__('Enable'),
                0 => Mage::helper('adminhtml')->__('Disable'),
            ),
        ));

        $this->addColumn('position', array(
            'header'   => Mage::helper('ayalinebillboard')->__('Position'),
            'width'    => '1',
            'type'     => 'number',
            'index'    => 'position',
            'editable' => true,
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/ayaline_billboard_category/billboardGrid', array('_current' => true));
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _getSelectedBillboards()
    {
        return array_keys($this->getSelectedBillboards());
    }

    public function getSelectedBillboards()
    {
        $billboards = array();
        foreach (Mage::getResourceSingleton('ayalinebillboard/billboard')->getBillboardCategoryPosition($this->getCategory()) as $_billboardId => $_position) {
            $billboards[$_billboardId] = array('position' => $_position);
        }

        return $billboards;
    }

    protected function _filterBillboardTypeCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addBillboardTypeFilter($value);
    }

    protected function _filterBillboardIdCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        //	"Fix" for ambiguous column 'billboard_id'
        $this->getCollection()->getSelect()->where('main_table.billboard_id = ?', $value);
    }

    protected function _filterCustomerGroupCondition($collection, $column)
    {
        $value = $column->getFilter()->getValue();
        if ($value == '') {
            return;
        }
        $this->getCollection()->addCustomerGroupFilter($value);
    }

    public function getTabLabel()
    {
        return Mage::helper('ayalinebillboard')->__('Billboard');
    }

    public function getTabTitle()
    {
        return Mage::helper('ayalinebillboard')->__('Billboard');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    public function getRowUrl($row)
    {
        return '#';
    }

    public function getRowClickCallback()
    {
        return '(function() { return false; })';
    }

}