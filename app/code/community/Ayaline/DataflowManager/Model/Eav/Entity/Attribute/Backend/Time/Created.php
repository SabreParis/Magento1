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
class Ayaline_DataflowManager_Model_Eav_Entity_Attribute_Backend_Time_Created extends Mage_Eav_Model_Entity_Attribute_Backend_Time_Created
{

    /**
     * {@inheritdoc}
     *
     * Do not re-save created_at value
     */
    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $date = $object->getData($attributeCode);
        if (is_null($date)) {
            if ($object->isObjectNew()) {
                $object->setData($attributeCode, Varien_Date::now());
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * Do not load created_at formatted for current store timezone
     */
    public function afterLoad($object)
    {
        return $this;
    }

}
