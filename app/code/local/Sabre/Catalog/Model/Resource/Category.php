<?php

/**
 * created: 2016
 *
 * @category  XXXXXXX
 * @package   Ayaline
 * @author    aYaline Magento <support.magento-shop@ayaline.com>
 * @copyright 2016 - aYaline Magento
 * @license   aYaline - http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 * @link      http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

class Sabre_Catalog_Model_Resource_Category extends Mage_Catalog_Model_Resource_Category
{
    public function getAttributeDefaultOptionValue(Mage_Catalog_Model_Category $category, $attributeCode){

        $attributeValue = $category->getData($attributeCode);

        $select = $this
            ->_getReadAdapter()
            ->select()
            ->from(array('at_opt_value' => $this->getTable('eav/attribute_option_value')))
            ->where(
                $this->_getReadAdapter()->quoteInto('at_opt_value.option_id=? AND at_opt_value.store_id = 0', $attributeValue)
            );

            $result = $this->_getReadAdapter()->fetchAll($select);


        if(!empty($result) && array_key_exists('value', $result)){
            return $result['value'];
        }
        return null;
    }
}
