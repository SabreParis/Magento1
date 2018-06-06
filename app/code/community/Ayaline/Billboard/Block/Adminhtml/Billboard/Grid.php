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
class Ayaline_Billboard_Block_Adminhtml_Billboard_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->setId('billboardGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('billboard_id');
        $this->setSaveParametersInSession(true);
        $this->setVarNameFilter('billboard_filter');
    }

    protected function _prepareCollection()
    {
        /* @var $collection Ayaline_Billboard_Model_Mysql4_Billboard_Collection */
        $collection = Mage::getResourceModel('ayalinebillboard/billboard_collection');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        Mage::helper('ayalinebillboard')->addBillboardTypeRendrerAndFilter($this);

        $this->addColumn('billboard_id', array(
            'header' => Mage::helper('ayalinebillboard')->__('ID'),
            'width'  => '50px',
            'index'  => 'billboard_id',
            'type'   => 'number',
        ));

        $this->addColumn('identifier', array(
            'header' => Mage::helper('ayalinebillboard')->__('Identifier'),
            'index'  => 'identifier',
            'type'   => 'text',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('ayalinebillboard')->__('Title'),
            'index'  => 'title',
            'type'   => 'text',
        ));

        $this->addColumn('display_from', array(
            'header'      => Mage::helper('ayalinebillboard')->__('Display from'),
            'index'       => 'display_from',
            'type'        => 'datetime',
            'filter_time' => true,
        ));

        $this->addColumn('display_to', array(
            'header'      => Mage::helper('ayalinebillboard')->__('Display to'),
            'index'       => 'display_to',
            'type'        => 'datetime',
            'filter_time' => true,
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
            'type'    => 'options',
            'options' => array(
                1 => Mage::helper('adminhtml')->__('Enable'),
                0 => Mage::helper('adminhtml')->__('Disable'),
            ),
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'                    => Mage::helper('ayalinebillboard')->__('Store View'),
                'index'                     => 'store_id',
                'type'                      => 'store',
                'store_all'                 => true,
                'store_view'                => true,
                'sortable'                  => false,
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('action', array(
            'header'    => Mage::helper('ayalinebillboard')->__('Action'),
            'width'     => '50',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('ayalinebillboard')->__('Edit'),
                    'url'     => array('base' => '*/*/edit'),
                    'field'   => 'billboard_id'
                ),
            ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('billboard_id' => $row->getId()));
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }

    protected function _filterBillboardTypeCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addBillboardTypeFilter($value);
    }

    protected function _filterCustomerGroupCondition($collection, $column)
    {
        $value = $column->getFilter()->getValue();
        if ($value == '') {
            return;
        }
        $this->getCollection()->addCustomerGroupFilter($value);
    }

}