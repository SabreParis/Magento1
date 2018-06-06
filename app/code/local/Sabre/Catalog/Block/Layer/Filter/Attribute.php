<?php

/**
 * created : 20/10/2015
 *
 * @category Ayaline
 * @package Ayaline_Billboard
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Catalog_Block_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Attribute
{

    const COLOR_ATTRIBUTE = 'a_filter_color';

    protected $attributesOptions;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     * @return Mage_Eav_Model_Resource_Entity_Attribute_Option_Collection
     */
    protected function _getAttributesOptions()
    {
        return Mage::helper('sabre_catalog')->getAttributesOptions();
    }

    public function getAttributeOptionDefaultValue($item){
        
        return $this->_getAttributeOptionDefaultValue($item);
    }
    
    protected function _getAttributeOptionDefaultValue($item)
    {
        $option = $this->_getAttributesOptions()
            ->getItemById($item->getValue());

        return $option ? $option->getValue() : false;
    }

    protected function _prepareFilter()
    {
        $this->_filter->setAttributeModel($this->getAttributeModel());

        return $this;
    }

    protected function _initFilter()
    {
        if (!$this->_filterModelName) {
            Mage::throwException(Mage::helper('catalog')->__('Filter model name must be declared.'));
        }
        $this->_filter = Mage::getModel($this->_filterModelName)
            ->setLayer($this->getLayer());
        $this->_prepareFilter();

        $attributeCode = $this->getAttributeModel()->getAttributeCode();
        if (preg_match('/^a_(article|model)/',$attributeCode)) {
            $this->setTemplate('sabre/catalog/layer/filter/by_text_attribute.phtml');
        } elseif ($attributeCode == 'a_filter_color') {
            $this->setTemplate('sabre/catalog/layer/filter/by_a_filter_color.phtml');
        }
        $this->_filter->apply($this->getRequest(), $this);
        return $this;
    }

    public function getAttributeClearUrl($_filterItem)
    {
        foreach ($this->getLayer()->getState()->getFilters() as $selectedFilter) {
            if ($selectedFilter->getName() == $_filterItem->getName()) {
                return $selectedFilter->getRemoveUrl();
            }
        }
        return null;
    }

    public function isCurrentFilter($_filterItem)
    {
        if (is_null($this->getFilteredLabel($_filterItem))) {
            return false;
        }
        return true;
    }

    public function getFilteredLabel($_filterItem)
    {
        foreach ($this->getLayer()->getState()->getFilters() as $selectedFilter) {
            if ($selectedFilter->getLabel() == $_filterItem->getLabel()) {
                return $selectedFilter->getLabel();
            }
        }
        return null;
    }

    public function getAttributeTextImage($attributeCode, $item)
    {
        $attributeValue = $this->_getAttributeOptionDefaultValue($item);

        return Mage::helper('sabre_catalog')->getProductAttributeImgUrl($attributeCode, $attributeValue, 'filters');
    }

    public function getColorPictoUrl($attributeCode, $attributeValue)
    {
        return Mage::helper('sabre_catalog')->getProductAttributeImgUrl($attributeCode, $attributeValue);
    }

    public function getItems()
    {
        return $this->getItemsByAlphabeticalOrder();
    }

    /**
     * Get items by alphabetical order.
     * @return array
     */
    private function getItemsByAlphabeticalOrder()
    {
        $items = parent::getItems();

        //Sort items by their label.
        usort($items, array($this, 'compare_label'));

        //Now we change the order for the display
        $nb_cols = 4;  //number of columns displayed on the site.
        $new_items = array();
        $nb_item = sizeof($items);
        $nb_rows_full = floor($nb_item / $nb_cols);
        $col = 0;
        $row = 0;
        $nbItemSup = $nb_item % $nb_cols;
        for ($i = 0; $i < $nb_item; $i++) {
            $new_items[$row][$col] = $items[$i];
            $row ++;
            if(($row == $nb_rows_full && ($col + 1) > $nbItemSup) || $row > $nb_rows_full){
                $row = 0;
                $col ++;
            }

            /*$pos = (($i % $nb_cols) * $nb_lignes) + (floor($i / $nb_cols));
            $new_items[] = $items[$pos];*/
        }
        return $new_items;
    }

    /**
     * Sort items by their label.
     * @param $a array
     * @param $b array
     * @return int
     */
    private function compare_label($a, $b)
    {
        return strnatcmp($a['label'], $b['label']);
    }



}
