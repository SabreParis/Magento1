<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Helper
 *
 * @author mzammouri
 */
class Sabre_Catalog_Model_Resource_Helper_Mysql4 extends Mage_Core_Model_Resource_Helper_Mysql4
{
    protected $attributeOptionValueCollectionCache;

    /**
     * 
     * @param array $attributesCode
     * @return Mage_Eav_Model_Resource_Entity_Attribute_Option_Collection
     */
    public function getEavAttributeOptionDefaultValueCollection(array $attributesCode)
    {
        $collectionCacheKey = implode('_', $attributesCode);
        if(!$this->attributeOptionValueCollectionCache || 
            !isset($this->attributeOptionValueCollectionCache[$collectionCacheKey])
            ){
            /* @var $optionsCollection Mage_Eav_Model_Resource_Entity_Attribute_Option_Collection */
            $optionsCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                ->setStoreFilter(0, false);

            $optionsCollection
                ->getSelect()
                ->join(
                    array('ea' => 'eav_attribute'), 
                    implode(
                        ' AND ', 
                        array(
                            'main_table.attribute_id=ea.attribute_id',
                            $this->_getReadAdapter()->quoteInto('ea.attribute_code IN (?)', $attributesCode),
                        )),
                    array('attribute_code'=>'attribute_code'))
            ;
            $this->attributeOptionValueCollectionCache[$collectionCacheKey] = $optionsCollection;
        }
        
        return $this->attributeOptionValueCollectionCache[$collectionCacheKey];
    }
}
