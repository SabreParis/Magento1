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
class Ayaline_Billboard_Block_Adminhtml_Type_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('billboardTypeGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('type_id');
        $this->setSaveParametersInSession(true);
        $this->setVarNameFilter('billboard_type_filter');
    }

    protected function _prepareCollection()
    {
        /* @var $collection Ayaline_Billboard_Model_Mysql4_Billboard_Type_Collection */
        $collection = Mage::getResourceModel('ayalinebillboard/billboard_type_collection');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('type_id', array(
            'header' => Mage::helper('ayalinebillboard')->__('ID'),
            'width'  => '50px',
            'index'  => 'type_id',
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

        $this->addColumn('action',
                         array(
                             'header'    => Mage::helper('ayalinebillboard')->__('Action'),
                             'width'     => '50',
                             'type'      => 'action',
                             'getter'    => 'getId',
                             'actions'   => array(
                                 array(
                                     'caption' => Mage::helper('ayalinebillboard')->__('Edit'),
                                     'url'     => array('base' => '*/*/edit'),
                                     'field'   => 'type_id'
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
        return $this->getUrl('*/*/edit', array('type_id' => $row->getId()));
    }

}