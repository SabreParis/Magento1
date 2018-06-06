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
class Ayaline_DataflowManager_Model_Resource_Catalog_Product extends Mage_Catalog_Model_Resource_Product
{

    protected $_dummy = null;

    /**
     * Public accessor for _saveWebsiteIds
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Mage_Catalog_Model_Resource_Product
     */
    public function saveWebsiteIds($product)
    {
        return $this->_saveWebsiteIds($product);
    }

    /**
     * Public accessor for _processAttributeValues
     *
     * @return Mage_Eav_Model_Entity_Abstract
     */
    public function processAttributeValues()
    {
        return $this->_processAttributeValues();
    }

    /**
     * Public accessor for _saveAttributeValue
     *
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * @param mixed                                     $value
     * @param int                                       $productId
     * @param int                                       $storeId
     * @return Mage_Catalog_Model_Resource_Abstract
     */
    public function setAttributeValueForSave($attribute, $value, $productId, $storeId)
    {
        if ($this->_dummy === null) {
            $this->_dummy = new Varien_Object();
        }
        $this->_dummy->addData(array(
                                   'entity_id' => $productId,
                                   'store_id'  => $storeId,
                               ));

        return $this->_insertAttribute($this->_dummy, $attribute, $value);
    }

    /**
     * {@inheritdoc}
     *
     * Change behavior to handle NULL value for default store
     */
    protected function _insertAttribute($object, $attribute, $value)
    {
        $storeId = (int)Mage::app()->getStore($object->getStoreId())->getId();

        if ($attribute->getIsRequired() && $this->getDefaultStoreId() != $storeId) {

            $table = $attribute->getBackend()->getTable();

            $select = $this->_getReadAdapter()->select()
                           ->from($table)
                           ->where('entity_type_id = ?', $attribute->getEntityTypeId())
                           ->where('attribute_id = ?', $attribute->getAttributeId())
                           ->where('store_id = ?', $this->getDefaultStoreId())
                           ->where('entity_id = ?', $object->getEntityId());
            $row = $this->_getReadAdapter()->fetchRow($select);

            if (!$row || !isset($row['value'])) {
                $data = new Varien_Object(
                    array(
                        'entity_type_id' => $attribute->getEntityTypeId(),
                        'attribute_id'   => $attribute->getAttributeId(),
                        'store_id'       => $this->getDefaultStoreId(),
                        'entity_id'      => $object->getEntityId(),
                        'value'          => $this->_prepareValueForSave($value, $attribute),
                    )
                );
                $bind = $this->_prepareDataForTable($data, $table);
                $this->_getWriteAdapter()->insertOnDuplicate($table, $bind, array('value'));
            }
        }

        return $this->_saveAttributeValue($object, $attribute, $value);
    }

    /**
     * Get product identifier by ayaline_uniq_id
     *
     * @param string $uniqId
     * @return int|false
     */
    public function getIdByUniqId($uniqId)
    {
        $sql = $this->_getReadAdapter()
                    ->select()
                    ->from($this->getEntityTable(), 'entity_id')
                    ->where('ayaline_uniq_id = ?', $uniqId);

        return $this->_getReadAdapter()->fetchOne($sql);
    }


    /**
     * {@inheritdoc}
     *
     * Remove uasort (performance issue)
     * Change backend model for created_at only for import
     * Change backend model for url_key only for import
     */
    public function getSortedAttributes($setId = null)
    {
        $attributes = $this->getAttributesByCode();
        if ($setId === null) {
            $setId = $this->getEntityType()->getDefaultAttributeSetId();
        }

        // initialize set info
        Mage::getSingleton('eav/entity_attribute_set')
            ->addSetInfo($this->getEntityType(), $attributes, $setId);

        foreach ($attributes as $code => $attribute) {
            /* @var $attribute Mage_Eav_Model_Entity_Attribute_Abstract */
            if (!$attribute->isInSet($setId)) {
                unset($attributes[$code]);
            } else {
                if ($attribute->getAttributeCode() == 'created_at') {
                    $attribute->setBackendModel('ayaline_dataflowmanager/eav_entity_attribute_backend_time_created');
                } elseif ($attribute->getAttributeCode() == 'url_key') {
                    $attribute->setBackendModel('ayaline_dataflowmanager/catalog_product_attribute_backend_urlkey');
                }
            }
        }

        $this->_sortingSetId = $setId;

//        uasort($attributes, array($this, 'attributesCompare'));

        return $attributes;
    }

    /**
     * {@inheritdoc}
     *
     * Change backend model for created_at only for import
     * Change backend model for url_key only for import
     */
    public function addAttribute(Mage_Eav_Model_Entity_Attribute_Abstract $attribute)
    {
        if ($attribute->getAttributeCode() == 'created_at') {
            $attribute->setBackendModel('ayaline_dataflowmanager/eav_entity_attribute_backend_time_created');
        } elseif ($attribute->getAttributeCode() == 'url_key') {
            $attribute->setBackendModel('ayaline_dataflowmanager/catalog_product_attribute_backend_urlkey');
        }

        return parent::addAttribute($attribute);
    }
}