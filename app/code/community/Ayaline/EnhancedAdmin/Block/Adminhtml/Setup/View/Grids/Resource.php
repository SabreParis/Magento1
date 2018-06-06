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
class Ayaline_EnhancedAdmin_Block_Adminhtml_Setup_View_Grids_Resource extends Ayaline_EnhancedAdmin_Block_Adminhtml_Setup_View_Grids_Abstract implements Ayaline_Core_Block_Adminhtml_Widget_Grid_Interface
{

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->setPagerVisibility(true);
        $this->setFilterVisibility(true);
        $this->setId('system_enhancedadmin_setup_view_grid_resource');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('ayaline_enhancedadmin/resourceSetup_collection');
        $collection->addModuleFilter($this->_getModule());

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        Mage::helper('ayaline_enhancedadmin')->addGridRendererAndFilter($this);

        $this->addColumn('code', array(
            'header'  => $this->__('Code'),
            'index'   => 'code',
            'type'    => 'options',
            'options' => Mage::getSingleton('ayaline_enhancedadmin/system_source_module_setupCode')->getOptions($this->_getModule(), false),
        ));

        $this->addColumn('type', array(
            'header'  => $this->__('Type'),
            'index'   => 'type',
            'type'    => 'options',
            'width'   => '150',
            'options' => Mage::getSingleton('ayaline_enhancedadmin/system_source_module_setupType')->getOptions(false, false),
        ));

        $this->addColumn('version', array(
            'header' => $this->__('Version'),
            'index'  => 'version',
            'type'   => 'text',
            'width'  => '100',
        ));

        $this->addColumn('applied', array(
            'header'  => $this->__('Is Applied'),
            'index'   => 'applied',
            'type'    => 'options',
            'width'   => '100',
            'options' => array(
                0 => Mage::helper('adminhtml')->__('No'),
                1 => Mage::helper('adminhtml')->__('Yes'),
            ),
        ));

        $this->addColumn('applied_at', array(
            'header' => $this->__('Applied at'),
            'index'  => 'applied_at',
            'type'   => 'datetime',
            'width'  => '150',
        ));

        $this->addColumn('view_file_action', array(
            'header'    => $this->__('View'),
            'type'      => 'view_file_action',
            'width'     => '100',
            'actions'   => array(
                array(
                    'caption' => $this->__('View'),
                    'field'   => 'hash',
                    'url'     => array(
                        'base'       => '*/*/viewFile',
                        'params'     => array(
                            'id' => $this->_getModule()->getId(),
                        ),
                        'row_params' => array(
                            'code' => 'getCode',
                            'type' => 'getType',
                            'file' => 'getVersion',
                        ),
                    ),
                ),
            ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ));

        $this->addColumn('file_action', array(
            'header'    => $this->__('Action'),
            'type'      => 'file_action',
            'width'     => '100',
            'actions'   => array(
                array(
                    'caption' => $this->__('Apply'),
                    'title'   => $this->__('Are you sure you want to proceed?'),
                    'confirm' => true,
                    'field'   => 'hash',
                    'url'     => array(
                        'base'       => '*/*/apply',
                        'params'     => array(
                            'id' => $this->_getModule()->getId(),
                        ),
                        'row_params' => array(
                            'code' => 'getCode',
                            'type' => 'getType',
                            'file' => 'getVersion',
                        ),
                    ),
                ),
            ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    public function getGridHeader()
    {
        return false;
    }

    public function canShowGrid()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/resourceGrid', array('_current' => true));
    }

}