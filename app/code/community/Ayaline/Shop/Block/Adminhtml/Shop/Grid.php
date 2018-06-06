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
 * Adminhtml shop grid block
 *
 */
class Ayaline_Shop_Block_Adminhtml_Shop_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('shopGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('shop_id');
        $this->setSaveParametersInSession(true);
    }

    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();
        $collection = Mage::getResourceModel('ayalineshop/shop_collection');
        $collection->addStoreFilter($store->getId());

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('shop_id', array(
            'header' => Mage::helper('ayalineshop')->__('ID'),
            'width'  => '50px',
            'index'  => 'shop_id',
            'type'   => 'number',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('ayalineshop')->__('Title'),
            'index'  => 'title',
            'type'   => 'text',
        ));

        $this->addColumn('group_id', array(
            'header'  => Mage::helper('ayalineshop')->__('Shop Group'),
            'index'   => 'group_id',
            'type'    => 'options',
            'options' => Mage::getModel('ayalineshop/shop_group')->getCollection()->addOrderByName()->getArray()
        ));

        $this->addColumn('postcode', array(
            'header' => Mage::helper('ayalineshop')->__('Postcode'),
            'index'  => 'postcode',
            'type'   => 'text',
        ));

        $this->addColumn('city', array(
            'header' => Mage::helper('ayalineshop')->__('City'),
            'index'  => 'city',
            'type'   => 'text',
        ));

        $this->addColumn('country_id', array(
            'header'  => Mage::helper('ayalineshop')->__('Country'),
            'index'   => 'country_id',
            'type'    => 'options',
            'options' => Mage::helper('ayalineshop')->getCountriesOption()
        ));

        $this->addColumn('is_active', array(
            'header'  => Mage::helper('ayalineshop')->__('State'),
            'index'   => 'is_active',
            'type'    => 'options',
            'width'   => '70px',
            'options' =>
                array(
                    true  => Mage::helper('adminhtml')->__('Yes'),
                    false => Mage::helper('adminhtml')->__('No')
                ),
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
        if (Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_shop/actions/update')) {
            $this->setMassactionIdField('shop_id');
            $this->getMassactionBlock()->setFormFieldName('shop');

            $this->getMassactionBlock()->addItem('delete', array(
                'label'   => Mage::helper('customer')->__('Delete'),
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
