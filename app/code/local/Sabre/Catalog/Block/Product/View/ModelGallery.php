<?php
/**
 * created : 13/10/2015
 *
 * @category Ayaline
 * @package Ayaline_Billboard
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

class Sabre_Catalog_Block_Product_View_ModelGallery extends Mage_Cms_Block_Block{

    const MODEL_PREFIX ='model-';

    protected function _beforeToHtml()
    {
        $this->addData(array('block_id' => $this->getProductAModel()));

        return parent::_beforeToHtml();
    }

    public function getProductAModel(){
        $product = Mage::registry('product');
        $model=$product->getResource()->getAttribute('a_model')
            ->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)
            ->getFrontend()->getValue($product);

        $attributeSetModel = Mage::getModel("eav/entity_attribute_set");
        $attributeSetModel->load($product->getAttributeSetId());
        $attributeSetName = $attributeSetModel->getAttributeSetName();
        return self::MODEL_PREFIX.$attributeSetName.'-'.$model;
    }
}