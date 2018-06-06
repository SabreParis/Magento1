<?php

/**
 * created: 2015
 *
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Adminhtml_Block_Catalog_Category_Helper_RelatedProductAttributeSet extends Varien_Data_Form_Element_Select
{

    public function getValues()
    {
        $productEntityTypeId = Mage::getSingleton('eav/config')->getEntityType(Mage_Catalog_Model_Product::ENTITY)->getId();

        /** @var $collection Mage_Eav_Model_Resource_Entity_Attribute_Set_Collection */
        $collection = Mage::getResourceModel('eav/entity_attribute_set_collection')
                          ->setEntityTypeFilter($productEntityTypeId);

        $return = [
            ['value' => '', 'label' => ''],
        ];

        foreach ($collection as $_attributeSet) {
            $return[] = ['value' => $_attributeSet->getId(), 'label' => $_attributeSet->getAttributeSetName()];
        }

        return $return;
    }

}
