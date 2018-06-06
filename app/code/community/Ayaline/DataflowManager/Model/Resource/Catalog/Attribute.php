<?php

/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_DataflowManager_Model_Resource_Catalog_Attribute extends Mage_Catalog_Model_Resource_Attribute
{

    protected $_loadedAttributes = array();

    /**
     * @param int $entityTypeId
     * @param int $attributeSetId
     * @return array
     */
    public function getAttributesByAttributeSetId($entityTypeId, $attributeSetId)
    {
        $sql = $this->_getReadAdapter()
                    ->select()
                    ->from(array('entity_attribute_table' => $this->getTable('eav/entity_attribute')))
                    ->joinInner(
                        array('attribute_table' => $this->getTable('eav/attribute')),
                        "entity_attribute_table.attribute_id = attribute_table.attribute_id",
                        array('attribute_code' => 'attribute_table.attribute_code')
                    )
                    ->where('entity_attribute_table.attribute_set_id = ?', $attributeSetId)
                    ->where('entity_attribute_table.entity_type_id = ?', $entityTypeId);

        $statement = $this->_getReadAdapter()->query($sql);

        $attributes = array();
        while ($_row = $statement->fetch()) {
            if (!array_key_exists($_row['attribute_code'], $this->_loadedAttributes)) {
                /** @var Mage_Catalog_Model_Resource_Eav_Attribute $_attribute */
                $_attribute = Mage::getModel('catalog/resource_eav_attribute')->load($_row['attribute_id']);
                $_attribute->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID);
                $_attributeOptions = null;
                if ($_attribute->usesSource() && ($_attribute->getSource() instanceof Mage_Eav_Model_Entity_Attribute_Source_Table)) {
                    $_attributeOptions = Mage::getResourceModel('eav/entity_attribute_option_collection')
                        ->setAttributeFilter($_row['attribute_id'])
                        ->setStoreFilter(Mage_Core_Model_App::ADMIN_STORE_ID)
                        ->toOptionArray();
                }

                $this->_loadedAttributes[$_row['attribute_code']] = array(
                    'attribute' => $_attribute,
                    'options'   => $_attributeOptions
                );
            }

            $attributes[$_row['attribute_code']] = $this->_loadedAttributes[$_row['attribute_code']];
        }

        return $attributes;
    }

    /**
     * @param int    $attributeId
     * @param string $optionValue
     * @param int    $storeId
     * @return string
     */
    public function addAttributeOption($attributeId, $optionValue, $storeId = Mage_Core_Model_App::ADMIN_STORE_ID)
    {
        // first check if this option exists
        //  if exists return option id
        //  if not add a new one and return option id

        $checkSql = $this->_getWriteAdapter()
                         ->select()
                         ->from(array('option_table' => $this->getTable('eav/attribute_option')), 'option_value_table.option_id')
                         ->joinInner(
                             array('option_value_table' => $this->getTable('eav/attribute_option_value')),
                             "option_value_table.option_id = option_table.option_id",
                             array()
                         )
                         ->where("option_table.attribute_id = ?", $attributeId)
                         ->where("option_value_table.store_id = ?", $storeId)
                         ->where("option_value_table.value = ?", $optionValue);
        $optionId = $this->_getWriteAdapter()->fetchOne($checkSql);

        if (!$optionId) {
            $this->_getWriteAdapter()->beginTransaction();
            try {
                $this->_getWriteAdapter()
                     ->insert(
                         $this->getTable('eav/attribute_option'),
                         array('attribute_id' => $attributeId)
                     );
                $optionId = $this->_getWriteAdapter()->lastInsertId($this->getTable('eav/attribute_option'));
                $this->_getWriteAdapter()
                     ->insert(
                         $this->getTable('eav/attribute_option_value'),
                         array(
                             'option_id' => $optionId,
                             'store_id'  => $storeId,
                             'value'     => $optionValue,
                         )
                     );

                $this->_getWriteAdapter()->commit();
            } catch (Exception $e) {
                $this->_getWriteAdapter()->rollBack();
                $optionId = false;
            }
        }

        return $optionId;
    }

    public function insertOrUpdateStoreLabels(Mage_Eav_Model_Attribute $attribute, $storeId, $defaultLabel, $label)
    {
        $adapter = $this->_getWriteAdapter();
        $adapter->beginTransaction();

        try {

            // Recherche de l'option avec le defaultLabel
            $optionSql = $adapter->select()
                ->from(array('option_table' => $this->getTable('eav/attribute_option')), "option_id")
                ->joinInner(
                    array('option_value_table' => $this->getTable('eav/attribute_option_value')),
                    "option_value_table.option_id = option_table.option_id",
                    array()
                )
                ->where("option_table.attribute_id = ?", $attribute->getId())
                ->where("option_value_table.store_id = ?", 0)
                ->where("option_value_table.value = ?", $defaultLabel)
            ;
            $optionId = $this->_getWriteAdapter()->fetchOne($optionSql);

            if ($optionId) {
                // Récupération du label
                $valueSql = $adapter->select()
                    ->from($this->getTable('eav/attribute_option_value'), "value_id")
                    ->where("store_id = ?", $storeId)
                    ->where("option_id = ?", $optionId);
                $valueId = $this->_getWriteAdapter()->fetchOne($valueSql);

                if ($valueId) {
                    // update
                    $adapter->update(
                        $this->getTable('eav/attribute_option_value'),
                        array(
                            'value' => $label,
                        ),
                        "option_id=$optionId and store_id=$storeId"
                    );
                } else {
                    // insert
                    $adapter->insert(
                        $this->getTable('eav/attribute_option_value'),
                        array(
                            'option_id' => $optionId,
                            'store_id' => $storeId,
                            'value' => $label,
                        )
                    );
                }
            }

        } catch(Exception $e) {
            Mage::logException($e);
            $adapter->rollBack();
            return $this;
        }

        $adapter->commit();
        return $this;
    }

}