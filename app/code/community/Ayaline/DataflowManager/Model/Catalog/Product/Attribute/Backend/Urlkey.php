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
class Ayaline_DataflowManager_Model_Catalog_Product_Attribute_Backend_Urlkey extends Mage_Catalog_Model_Product_Attribute_Backend_Urlkey
{

    /**
     * {@inheritdoc}
     *
     * Done directly into import (\Ayaline_DataflowManager_Model_Import_Catalog_Product::_initDefaultUrlKey)
     */
    public function beforeSave($object)
    {
        return $this;
    }

}
