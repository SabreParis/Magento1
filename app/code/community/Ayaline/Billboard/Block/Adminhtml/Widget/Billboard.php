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
class Ayaline_Billboard_Block_Adminhtml_Widget_Billboard extends Mage_Adminhtml_Block_Widget_Grid
{

    protected $_selectedBillboards = array();

    protected function _prepareLayout()
    {
        $jsFunction = <<<JS
function getData(){
	var arr = new Array();
	$$('#{$this->getId()} input.checkbox:checked').each(function(elem){ arr.push(elem.value); });
	var values = arr.join(',');
	$('{$this->getId()}value').setAttribute('value', values);
	$('{$this->getId()}label').update(values);
}; getData(); 
JS;

        $this->setChild('add_billboard_button',
                        $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                                                                                                'label'   => Mage::helper('ayalinebillboard')->__('Add'),
                                                                                                'onclick' => $jsFunction . $this->getId() . '.close();',
                                                                                                'class'   => 'task'
                                                                                            ))
        );

        return parent::_prepareLayout();
    }

    public function getMainButtonsHtml()
    {
        $html = parent::getMainButtonsHtml();
        if ($this->getFilterVisibility()) {
            $html .= $this->getChildHtml('add_billboard_button');
        }

        return $html;
    }

    /**
     * Block construction, prepare grid params
     *
     * @param array $arguments
     */
    public function __construct($arguments = array())
    {
        parent::__construct($arguments);
        $this->setDefaultSort('title');
        $this->setUseAjax(true);
        $this->setUseMassaction(true);
    }

    /**
     * Prepare chooser element HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());

        $sourceUrl = $this->getUrl('*/ayaline_billboard_widget/chooser', array(
            'uniq_id'        => $uniqId,
            'use_massaction' => $this->getUseMassaction(),
        ));

        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
                        ->setElement($element)
                        ->setTranslationHelper($this->getTranslationHelper())
                        ->setConfig($this->getConfig())
                        ->setFieldsetId($this->getFieldsetId())
                        ->setSourceUrl($sourceUrl)
                        ->setUniqId($uniqId);

        if ($element->getValue()) {
            $values = explode(',', $element->getValue());
            $chooser->setLabel($element->getValue());
        }

        $element->setData('after_element_html', $chooser->toHtml());

        return $element;
    }

    /**
     * Checkbox Check JS Callback
     *
     * @return string
     */
    public function getCheckboxCheckCallback()
    {
        if ($this->getUseMassaction()) {
            return "function (grid, event) { $(grid.containerId).fire('ayalinebillboard:changed', {}); }";
        }
    }

    /**
     * Grid Row JS Callback
     *
     * @return string
     */
    public function getRowClickCallback()
    {
        if (!$this->getUseMassaction()) {
            $chooserJsObject = $this->getId();

            return '
				function (grid, event) {
					var trElement = Event.findElement(event, "tr");
					var billboardIdentifier = trElement.down("td").innerHTML;
					var billboardTitle = trElement.down("td").next().next().innerHTML;
					var optionLabel = billboardTitle;
					var optionValue = "billboard/" + billboardIdentifier.replace(/^\s+|\s+$/g,"");
					' . $chooserJsObject . '.setElementValue(optionValue);
					' . $chooserJsObject . '.setElementLabel(optionLabel);
					' . $chooserJsObject . '.close();
				}
			';
        }
    }

    /**
     * Filter checked/unchecked rows in grid
     *
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Ayaline_Billboard_Block_Adminhtml_Widget_Billboard
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'selected_billboards') {
            $selected = $this->getSelectedBillboards();
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('identifier', array('in' => $selected));
            } else {
                $this->getCollection()->addFieldToFilter('identifier', array('nin' => $selected));
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * Prepare billboards collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
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

        if ($this->getUseMassaction()) {
            $this->addColumn('selected_billboards', array(
                'header_css_class' => 'a-center',
                'type'             => 'checkbox',
                'name'             => 'selected_billboards',
                'inline_css'       => 'checkbox entities',
                'field_name'       => 'selected_billboards',
                'values'           => $this->getSelectedBillboards(),
                'align'            => 'center',
                'index'            => 'identifier',
                'use_index'        => true,
            ));
        }

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

        return parent::_prepareColumns();
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

    /**
     * Adds additional parameter to URL for loading only billboards grid
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/ayaline_billboard_widget/chooser', array(
            'billboards_grid' => true,
            '_current'        => true,
            'uniq_id'         => $this->getId(),
            'use_massaction'  => $this->getUseMassaction(),
        ));
    }

    public function getSelectedBillboards()
    {
        if ($selectedBillboards = $this->getRequest()->getParam('element_value', null)) {
            $selectedBillboards = explode(',', $selectedBillboards);
            $this->setSelectedBillboards($selectedBillboards);
        }

        return $this->_selectedBillboards;
    }

    public function setSelectedBillboards($selectedBillboards)
    {
        $this->_selectedBillboards = $selectedBillboards;

        return $this;
    }

}