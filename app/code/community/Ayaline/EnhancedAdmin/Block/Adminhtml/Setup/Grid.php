<?php

/**
 * created : 2013
 *
 * @category  Ayaline
 * @package   Ayaline_EnhancedAdmin
 * @author    aYaline
 * @copyright Ayaline - 2013 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_EnhancedAdmin_Block_Adminhtml_Setup_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->setId('system_enhancedadmin_setup_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('name');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Ayaline_EnhancedAdmin_Model_Resource_Module_Collection */
        $collection = Mage::getResourceModel('ayaline_enhancedadmin/module_collection');

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        Mage::helper('ayaline_enhancedadmin')->addGridRendererAndFilter($this);

        $this->addColumn('name', array(
            'header' => $this->__('Name'),
            'index'  => 'name',
            'type'   => 'text',
        ));

        $this->addColumn('version', array(
            'header' => $this->__('Version (config.xml)'),
            'index'  => 'version',
            'type'   => 'text',
        ));

        $this->addColumn('versions', array(
            'header'   => $this->__('Versions (core_resource)'),
            'index'    => 'version',
            'type'     => 'setup_versions',
            'filter'   => false,
            'sortable' => false,
        ));

        $this->addColumn('code_pool', array(
            'header'  => $this->__('Code pool'),
            'index'   => 'code_pool',
            'width'   => '70',
            'type'    => 'options',
            'options' => array(
                'community' => $this->__('Community'),
                'core'      => $this->__('Core'),
                'local'     => $this->__('Local'),
            ),
        ));

        $this->addColumn('is_active', array(
            'header'  => $this->__('Is Active'),
            'index'   => 'is_active',
            'width'   => '70',
            'type'    => 'options',
            'options' => array(
                0 => $this->__('No'),
                1 => $this->__('Yes'),
            ),
        ));

        $this->addColumn('action', array(
            'header'    => $this->__('Action'),
            'width'     => '50',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption' => $this->__('View'),
                    'url'     => array(
                        'base' => '*/*/view',
                    ),
                    'field'   => 'id',
                ),
            ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    /**
     * @param Varien_Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array(
            'id' => $row->getId(),
        ));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

}