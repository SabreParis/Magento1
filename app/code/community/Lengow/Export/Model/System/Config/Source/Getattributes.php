<?php

/**
 * Lengow export model system config source getattributes
 *
 * @category    Lengow
 * @package     Lengow_Export
 * @author      Team Connector <team-connector@lengow.com>
 * @copyright   2016 Lengow SAS
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Lengow_Export_Model_System_Config_Source_Getattributes extends Mage_Core_Model_Config_Data
{

    public function toOptionArray()
    {
        $attribute = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')
                ->getResource()
                ->getTypeId());
        $attributeArray = array();
        $attributeArray[] = array(
            'value' => 'none',
            'label' => '',
        );
        foreach ($attribute as $option) {
            $attributeArray[] = array(
                'value' => $option->getAttributeCode(),
                'label' => $option->getAttributeCode()
            );
        }
        return $attributeArray;
    }
}
