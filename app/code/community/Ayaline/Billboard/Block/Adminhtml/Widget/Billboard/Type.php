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
class Ayaline_Billboard_Block_Adminhtml_Widget_Billboard_Type extends Mage_Adminhtml_Block_Widget_Grid
{

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

        $sourceUrl = $this->getUrl('*/ayaline_billboard_type_widget/chooser', array(
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
					var typeIdentifier = trElement.down("td").next().innerHTML.strip();
					var typeTitle = trElement.down("td").next().innerHTML.strip();
					var optionLabel = typeTitle;
					var optionValue = typeIdentifier.replace(/^\s+|\s+$/g,"");
					' . $chooserJsObject . '.setElementValue(optionValue);
					' . $chooserJsObject . '.setElementLabel(optionLabel);
					' . $chooserJsObject . '.close();
				}
			';
        }
    }

    /**
     * Prepare billboards collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
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

        return parent::_prepareColumns();
    }

    /**
     * Adds additional parameter to URL for loading only billboards grid
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/ayaline_billboard_type_widget/chooser', array(
            'types_grid'     => true,
            '_current'       => true,
            'uniq_id'        => $this->getId(),
            'use_massaction' => $this->getUseMassaction(),
        ));
    }

}