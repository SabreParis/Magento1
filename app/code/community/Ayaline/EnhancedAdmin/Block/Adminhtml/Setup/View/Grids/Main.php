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
class Ayaline_EnhancedAdmin_Block_Adminhtml_Setup_View_Grids_Main extends Ayaline_EnhancedAdmin_Block_Adminhtml_Setup_View_Grids_Abstract implements Ayaline_Core_Block_Adminhtml_Widget_Grid_Interface
{

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->setId('system_enhancedadmin_setup_view_grid_main');
    }

    protected function _prepareCollection()
    {
        $collection = $this->_getModule()->getAvailableSetups();

        if ($code = $this->getRequest()->getParam('code', false)) {
            foreach ($collection as $_item) {
                if ($_item->getCode() == $code) {
                    $_item->setFile($this->getRequest()->getParam('file', ''));
                    $_item->setType($this->getRequest()->getParam('type', ''));
                }
            }
        }

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        Mage::helper('ayaline_enhancedadmin')->addGridRendererAndFilter($this);

        $this->addColumn('code', array(
            'header'   => $this->__('Code'),
            'index'    => 'code',
            'type'     => 'text',
            'filter'   => false,
            'sortable' => false,
        ));

        $this->addColumn('db_version', array(
            'header'   => $this->__('Sql version'),
            'index'    => 'db_version',
            'type'     => 'text',
            'width'    => '100',
            'filter'   => false,
            'sortable' => false,
        ));

        $this->addColumn('data_version', array(
            'header'   => $this->__('Data version'),
            'index'    => 'data_version',
            'type'     => 'text',
            'width'    => '100',
            'filter'   => false,
            'sortable' => false,
        ));

        $this->addColumn('file', array(
            'header'   => $this->__('File'),
            'index'    => 'file',
            'type'     => 'setup_version',
            'filter'   => false,
            'sortable' => false,
            'width'    => '300',
            'mode'     => 'file',
        ));

        $this->addColumn('type', array(
            'header'   => $this->__('Type'),
            'index'    => 'type',
            'type'     => 'select',
            'width'    => '150',
            'filter'   => false,
            'sortable' => false,
            'options'  => Mage::getSingleton('ayaline_enhancedadmin/system_source_module_setupType')->getOptions(true),
        ));

        $this->addColumn('view_file_action', array(
            'header'    => $this->__('View'),
            'type'      => 'view_file_action',
            'width'     => '100',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption' => $this->__('View'),
                    'field'   => 'code',
                    'url'     => array(
                        'base'   => '*/*/viewFile',
                        'params' => array(
                            'id' => $this->_getModule()->getId(),
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
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption' => $this->__('Apply'),
                    'title'   => $this->__('Are you sure you want to proceed?'),
                    'confirm' => true,
                    'field'   => 'code',
                    'url'     => array(
                        'base'   => '*/*/apply',
                        'params' => array(
                            'id' => $this->_getModule()->getId(),
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

}