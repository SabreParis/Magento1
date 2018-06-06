<?php

/**
 * created: 2014
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_DataflowManager_Block_Adminhtml_DataflowManager_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->setId('system_enhancedadmin_dataflowmanager_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('name');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Ayaline_DataflowManager_Model_Resource_DataflowManager_Collection */
        $collection = Mage::getResourceModel('ayaline_dataflowmanager/dataflowManager_collection');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('code', array(
            'header' => $this->__('Dataflow Code'),
            'index'  => 'code',
            'type'   => 'text',
        ));

        $this->addColumn('name', array(
            'header' => $this->__('Dataflow Name'),
            'index'  => 'name',
            'type'   => 'text',
        ));

        $this->addColumn('status', array(
            'header'         => Mage::helper('index')->__('Status'),
            'width'          => '120',
            'align'          => 'left',
            'index'          => 'status',
            'type'           => 'options',
            'options'        => Mage::getSingleton('ayaline_dataflowmanager/system_source_status')->getOptions(),
            'frame_callback' => array($this, 'decorateStatus')
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowClickCallback()
    {
        return "(function() { return false; })";
    }

    /**
     * Decorate status column values
     *
     * @param string                                  $value
     * @param Mage_Index_Model_Process                $row
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @param bool                                    $isExport
     *
     * @return string
     */
    public function decorateStatus($value, $row, $column, $isExport)
    {
        $class = '';
        switch ($row->getStatus()) {
            case Ayaline_DataflowManager_Model_System_Source_Status::STATUS_NOT_RUNNING :
                $class = 'grid-severity-notice';
                break;
            case Ayaline_DataflowManager_Model_System_Source_Status::STATUS_RUNNING :
                $class = 'grid-severity-major';
                break;
        }

        return '<span class="' . $class . '"><span>' . $value . '</span></span>';
    }

} 