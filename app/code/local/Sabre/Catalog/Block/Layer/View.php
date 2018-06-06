<?php

class Sabre_Catalog_Block_Layer_View extends Mage_Catalog_Block_Layer_View
{
    const COLOR_ATTRIBUTE = 'a_filter_color';

    protected function _prepareLayout()
    {
        $stateBlock = $this->getLayout()->createBlock($this->_stateBlockName)
            ->setLayer($this->getLayer());


        $this->setChild('layer_state', $stateBlock);

        $filterableAttributes = $this->_getFilterableAttributes();
        foreach ($filterableAttributes as $attribute) {
            if ($attribute->getAttributeCode() == 'price') {
                continue;
            } elseif ($attribute->getBackendType() == 'decimal') {
                continue;
            } else {
                $filterBlockName = $this->_attributeFilterBlockName;
            }

            $child = $this->getLayout()->createBlock($filterBlockName)
                ->setLayer($this->getLayer())
                ->setAttributeModel($attribute)
                ->init();
            $this->setChild($attribute->getAttributeCode() . '_filter', $child);
        }

        $this->getLayer()->apply();

        return $this;
    }

    public function getFilters()
    {
        $filters = array();
        $filterableAttributes = $this->_getFilterableAttributes();

        foreach ($filterableAttributes as $attribute) {
            $filters[] = $this->getChild($attribute->getAttributeCode() . '_filter');
        }
        return $filters;
    }

    public function getFilteredLabel($filterItem)
    {
        foreach ($this->getLayer()->getState()->getFilters() as $selectedFilter) {
            if ($selectedFilter->getName() == $filterItem->getName()) {
                return $selectedFilter->getLabel();
            }
        }
        return null;
    }

    /**
     * @param Mage_Catalog_Model_Layer_Filter_Item $filterItem
     * @return string
     */
    public function getSelectedFilterAdminLabel($currentFilter)
    {
        if ($currentFilter == self::COLOR_ATTRIBUTE) {

            $entityType = Mage::getModel('eav/config')->getEntityType('catalog_product');

            $attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode($entityType, $currentFilter);

            $attributeOptionId = $this->getRequest()->getParam($currentFilter);

            $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                ->setAttributeFilter($attributeModel->getId())
                ->setStoreFilter(0)
                ->load();
            foreach ($_collection->toOptionArray() as $_cur_option) {
                if ($_cur_option['value'] == $attributeOptionId) {
                    return $_cur_option['label'];
                }
            }

        }
        return null;
    }

}