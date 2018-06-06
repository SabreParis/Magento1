<?php
/**
 * created : 10/03/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Shop
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */


/**
 * Adminhtml shop group grid block
 *
 */
class Ayaline_Shop_Block_Adminhtml_Shop_Group_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('groupGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('group_id');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('ayalineshop/shop_group_collection');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('group_id', array(
            'header' => Mage::helper('ayalineshop')->__('ID'),
            'width'  => '50px',
            'index'  => 'group_id',
            'type'   => 'number',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('ayalineshop')->__('Name'),
            'index'  => 'name'
        ));

        $this->addColumn('action',
                         array(
                             'header'    => Mage::helper('ayalineshop')->__('Action'),
                             'width'     => '100',
                             'type'      => 'action',
                             'getter'    => 'getId',
                             'actions'   => array(
                                 array(
                                     'caption' => Mage::helper('ayalineshop')->__('Edit'),
                                     'url'     => array('base' => '*/*/edit'),
                                     'field'   => 'id'
                                 )
                             ),
                             'filter'    => false,
                             'sortable'  => false,
                             'is_system' => true,
                         ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        if (Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_group/actions/update')) {
            $this->setMassactionIdField('group_id');
            $this->getMassactionBlock()->setFormFieldName('groups');

            $this->getMassactionBlock()->addItem('delete', array(
                'label'   => Mage::helper('ayalineshop')->__('Delete'),
                'url'     => $this->getUrl('*/*/massDelete'),
                'confirm' => Mage::helper('ayalineshop')->__('Are you sure?')
            ));
        }

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
