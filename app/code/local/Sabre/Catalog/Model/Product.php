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
class Sabre_Catalog_Model_Product extends Mage_Catalog_Model_Product
{

    /**
     * Same as getAttributeText but we force storeId on attribute
     *
     * @param string $attributeCode
     * @return string
     */
    public function getStoreAttributeText($attributeCode)
    {
        return $this->getResource()
                    ->getAttribute($attributeCode)
                    ->setStoreId(Mage::app()->getStore()->getId()) // force store id
                    ->getSource()
                    ->getOptionText($this->getData($attributeCode));
    }

}
