<?php
/*
 * Author Rudyuk Vitalij Anatolievich
 * Email rvansp@gmail.com
 * Blog www.cervic.info
 */
?>
<?php

class Infomodus_Upsap_Block_Adminhtml_Method_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('methodGrid');
        $this->setDefaultSort('upsapshippingmethod_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('upsap/method')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('upsapshippingmethod_id', array(
            'header' => Mage::helper('upsap')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'upsapshippingmethod_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('upsap')->__('Title'),
            'align' => 'left',
            'width' => '50px',
            'index' => 'title',
            'type'  => 'text',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('upsap')->__('Method Name'),
            'align' => 'left',
            'width' => '50px',
            'index' => 'name',
            'type'  => 'text',
        ));

        $this->addColumn('upsmethod_id', array(
            'header' => Mage::helper('upsap')->__('UPS Shipping Method'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'upsmethod_id',
            'type'  => 'options',
            'options' => Mage::getModel('upslabel/config_upsmethod')->getUpsMethods(),
        ));
        /*multistore*/
        $this->addColumn('store_id', array(
            'header' => Mage::helper('upsap')->__('Store'),
            'align' => 'left',
            'width' => '100px',
            'index' => 'store_id',
            'type'  => 'options',
            'options' => Mage::getModel('upslabel/config_pickup_stores')->getStores(),
        ));
        /*multistore*/
        $this->addColumn('status', array(
            'header' => Mage::helper('upsap')->__('Status'),
            'align' => 'left',
            'width' => '50px',
            'index' => 'status',
            'type'  => 'options',
            'options' => array('1' => Mage::helper('adminhtml')->__('Enabled'), '0' => Mage::helper('adminhtml')->__('Disabled'))
        ));

        $this->addColumn('action',
            array(
                'header' => Mage::helper('upsap')->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('upsap')->__('Edit'),
                        'url' => array('base' => '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));

        $this->addExportType('*/*/exportCsv', Mage::helper('upsap')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('upsap')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('upsapshippingmethod_id');
        $this->getMassactionBlock()->setFormFieldName('method');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('upsap')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('upsap')->__('Are you sure?')
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}