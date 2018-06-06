<?php

/**
 * Created by PhpStorm.
 * User: Manal
 * Date: 18/09/2015
 * Time: 11:00
 */
class Sabre_Configuration_Helper_Data extends Mage_Core_Helper_Abstract
{

    const ATTR_IMB_BASE_DIR = 'images/product_map';

    //product attributes names.
    const AModelAttribute = 'a_model';
    const AFilterColorAttribute = 'a_filter_color';
    const AArticleAttribute = 'a_article';

    /**
     * @param string $attributeCode
     * @return array
     */
    public function getAllProductAttributeImage($attributeCode)
    {
        $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attributeCode);
        $fullPaths = array();

        if ($attribute->usesSource()) {
            $options = $attribute->setStoreId(0)->getSource()->getAllOptions(false);
            $skinBaseSir = Mage::getConfig()->getNode('product_attributes_images_mapping/skin_base_dir');

            foreach ($options as $option) {
                $optionCode = $option['label']; // Admin label option
                $imgSrc = Mage::getConfig()->getNode("product_attributes_images_mapping/attributes/$attributeCode/$optionCode/image_src");

                $fullPaths[] = array(
                    'url'   => Mage::getDesign()->getSkinUrl($skinBaseSir . '/' . $imgSrc),
                    'label' => $attribute->setStoreId(Mage::app()->getStore()->getId())->getSource()->getOptionText($option['value']),
                    'value' => $option['value'],
                );
            }

        }

        return $fullPaths;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param string                     $attributeCode
     * @return array
     */
    public function getProductAttributeAdminLabel($product, $attributeCode)
    {
        $options = explode(",", $product->getResource()->getAttribute($attributeCode)->setStoreId(0)->getFrontend()->getValue($product));
        $labels = array();

        foreach ($options as $option) {
            $option = trim($option);
            $labels[] = array(
                'label' => $option,
            );
        }

        return $labels;
    }

    /**
     * @param string $attributeCode
     * @return array
     */
    public function getAllProductAttributeAdminLabel($attributeCode)
    {
        $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attributeCode);
        $labels = array();
        if ($attribute->usesSource()) {
            $options = $attribute->setStoreId(0)->getSource()->getAllOptions(false);
            foreach ($options as $option) {
                $label = $attribute->setStoreId(Mage::app()->getStore()->getId())->getSource()->getOptionText($option['value']);

                $labels[] = array(
                    'label' => $label,
                );
            }
        }

        return $labels;
    }



    public function getPageUrlByIdentifier($pageIdentifier = null)
    {
        $page = Mage::getModel('cms/page');
        if (!is_null($pageIdentifier)) {
            $page->setStoreId(Mage::app()->getStore()->getId());
            if (!$page->load($pageIdentifier,'identifier')) {
                return null;
            }
        }
        if (!$page->getId()) {
            return null;
        }

        return Mage::getUrl(null, array('_direct' => $page->getIdentifier()));
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @to add configuration
     * @return boolean
     */

    public function getProductTypeCuilliere($product){
        $productType = $product->getResource()->getAttribute('a_article')->setStoreId(0)->getFrontend()->getValue($product);
        if($productType == 'cuillere-a-cafe' || $productType == 'cuillere-a-dessert' ||
            $productType == 'cuillere-a-sauce' || $productType == 'cuillere-de-table' ||
            $productType == 'cuillere-moka' || $productType == 'cuillere-a-servir' ||
            $productType == 'cuillere-a-melange')
        {
            return true;
        }
        return false;
    }

}
