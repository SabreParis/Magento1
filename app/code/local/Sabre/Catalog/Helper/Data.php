<?php

/**
 * Created : 2015
 *
 * @category  Sabre
 * @package   Sabre_Catalog
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Catalog_Helper_Data extends Mage_Core_Helper_Abstract
{

    const ATTRIBUTE_CODE_IS_SET = 'a_is_set';
    const ATTRIBUTE_CODE_FILTER_COLOR = 'a_filter_color';
    const ATTRIBUTE_CODE_COLOR = 'color';
    const ATTRIBUTE_CODE_MODEL = 'a_model';
    const ATTRIBUTE_CODE_ARTICLE = 'a_article';

    const ATTRIBUTE_SET_CUTLERY = 'cutlery';
    const ATTRIBUTE_SET_PORCELAIN = 'porcelain';
    const ATTRIBUTE_SET_ACCESSORY = 'accessory';

    protected $_attributeSets = [];

    protected $_attributesOptions = null;

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    protected function _getProductAttributeSetCode($product)
    {
        if (!isset($this->_attributeSets[$product->getData('attribute_set_id')])) {
            /** @var Mage_Eav_Model_Resource_Entity_Attribute_Set_Collection $sets */
            $sets = Mage::getResourceModel('eav/entity_attribute_set_collection');
            $sets->addFieldToFilter('attribute_set_id', ['eq' => $product->getData('attribute_set_id')]);
            $sets->setPageSize(1);

            $this->_attributeSets[$product->getData('attribute_set_id')] = $sets->getFirstItem()->getData('attribute_set_code');
        }

        return $this->_attributeSets[$product->getData('attribute_set_id')];
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    public function getProductAttributeSetCode($product)
    {
        return $this->_getProductAttributeSetCode($product);
    }

    public function getProductAArticleAttributeCode($product)
    {
        $attributeCode = $this->_getProductAttributeSetCode($product);
        if ($attributeCode && $attributeCode != 'default') {
            return self::ATTRIBUTE_CODE_ARTICLE . "_{$attributeCode}";
        }

        return self::ATTRIBUTE_CODE_ARTICLE;
    }

    public function getProductAModelAttributeCode($product)
    {
        $attributeCode = $this->_getProductAttributeSetCode($product);
        if ($attributeCode && $attributeCode != 'default') {
            return self::ATTRIBUTE_CODE_MODEL . "_{$attributeCode}";
        }

        return self::ATTRIBUTE_CODE_MODEL;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return bool|Mage_Catalog_Model_Category
     * @throws Mage_Core_Exception
     */
    public function getMainCategoryByProduct($product)
    {
        if ($product instanceof Mage_Catalog_Model_Product) {
            $categories = $product->getCategoryCollection();
        } else {
            $categories = Mage::getResourceModel('catalog/category_collection')
                ->joinField(
                    'product_id', 'catalog/category_product', 'product_id', 'category_id = entity_id', null
                )
                ->addFieldToFilter('product_id', (int)$product);
        }

        $categories->addAttributeToSelect(['name', 'is_anchor', 'url_key']);
        $categories->addOrder('level', Varien_Data_Collection_Db::SORT_ORDER_DESC);
        $categories->setPageSize(1);

        return $categories->getSize() ? $categories->getFirstItem() : false;
    }

    /**
     *
     * @param string $attributeCode
     * @param string $attributeValue
     * @param string $fileExtension
     * @return boolean
     */
    public function getProductAttributeImgUrl($attributeCode, $attributeValue, $subDirectory = null, $fileExtension = null)
    {
        $_fileExtension = is_null($fileExtension) ? $this->getProductAttributeImgExt($attributeCode) : $fileExtension;
        $_fileName = $attributeCode . ($subDirectory ? DS . $subDirectory : '') . DS . $attributeValue . '.' . $_fileExtension;
        $mediaBasePath = 'catalog/attribute/';

        if (file_exists(Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $mediaBasePath . $_fileName)) {
            return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $mediaBasePath . $_fileName;
        } elseif (file_exists(Mage::getDesign()->getFilename('images/product_map' . DS . $_fileName, array('_type' => 'skin')))) {
            return Mage::getDesign()->getSkinUrl('images/product_map' . DS . $_fileName);
        } else {
            if (!is_null($subDirectory)) {
                // re-ierate to get image without subdirectory
                return $this->getProductAttributeImgUrl($attributeCode, $attributeValue, null, $fileExtension);
            }

            return false;
        }
    }

    /**
     *
     * @param string $code
     * @return string
     */
    public function getProductAttributeImgExt($code)
    {
        $mapping = array(
            'color' => 'jpg',
            'a_filter_color' => 'jpg',
            'a_article' => 'png',
            'a_article_' . self::ATTRIBUTE_SET_CUTLERY => 'png',
            'a_article_' . self::ATTRIBUTE_SET_ACCESSORY => 'png',
            'a_article' . self::ATTRIBUTE_SET_PORCELAIN => 'png',
            'a_model' => 'png',
            'a_model' . self::ATTRIBUTE_SET_CUTLERY => 'png',
            'a_model' . self::ATTRIBUTE_SET_ACCESSORY => 'png',
            'a_model' . self::ATTRIBUTE_SET_PORCELAIN => 'png',
            '_default' => 'png'
        );

        return array_key_exists($code, $mapping) ? $mapping[$code] : $mapping['_default'];
    }

    /**
     * Build product name from three parameters a_model, color, a_article
     *
     *
     * @param Sabre_Catalog_Model_Product $product
     * @return string
     */
    public function getCustomProductName($product)
    {
        return implode(' - ', array(
                                $product->getStoreAttributeText('a_model'),
                                $product->getStoreAttributeText('a_article'),
                                $product->getStoreAttributeText('color'),
                            )
        );
    }

    public function getCustomProductNameWithHtml($product)
    {
        $productName = <<<HTML
      <span>{$product->getAttributeText('a_model')}</span> - {$product->getAttributeText('a_article')} - {$product->getAttributeText('color')}
HTML;

        return $productName;
    }


    public function getSalesItemCustomProductNameWithHtml($item)
    {
        $_model = Mage::getResourceSingleton('catalog/product')
            ->getAttribute('a_model')
            ->getSource()
            ->getOptionText($item->getData('a_model'));

        $_article = Mage::getResourceSingleton('catalog/product')
            ->getAttribute('a_article')
            ->getSource()
            ->getOptionText($item->getData('a_article'));

        $_color = Mage::getResourceSingleton('catalog/product')
            ->getAttribute('color')
            ->getSource()
            ->getOptionText($item->getData('color'));

        $productName = <<<HTML
      <span>{$_model}</span> - {$_article} - {$_color}
HTML;

        return $productName;
    }


    /**
     *
     * @return Mage_Eav_Model_Resource_Entity_Attribute_Option_Collection
     */
    public function getAttributesOptions()
    {
        if ($this->_attributesOptions === null) {

            /* @var $options Mage_Eav_Model_Resource_Entity_Attribute_Option_Collection */
            $options = Mage::getResourceHelper('sabre_catalog')
                ->getEavAttributeOptionDefaultValueCollection(array(
                    Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_ARTICLE,
                    Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_ARTICLE . '_' . Sabre_Catalog_Helper_Data::ATTRIBUTE_SET_CUTLERY,
                    Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_ARTICLE . '_' . Sabre_Catalog_Helper_Data::ATTRIBUTE_SET_ACCESSORY,
                    Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_ARTICLE . '_' . Sabre_Catalog_Helper_Data::ATTRIBUTE_SET_PORCELAIN,
                    Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_MODEL,
                    Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_MODEL . '_' . Sabre_Catalog_Helper_Data::ATTRIBUTE_SET_CUTLERY,
                    Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_MODEL . '_' . Sabre_Catalog_Helper_Data::ATTRIBUTE_SET_ACCESSORY,
                    Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_MODEL . '_' . Sabre_Catalog_Helper_Data::ATTRIBUTE_SET_PORCELAIN,
                    Sabre_Catalog_Helper_Data::ATTRIBUTE_CODE_FILTER_COLOR,
                ));

            $this->_attributesOptions = $options->load();
        }

        return $this->_attributesOptions;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param string $attributeCode
     * @return bool|string
     */
    public function getAttributeOptionDefaultValue($product, $attributeCode)
    {
        $option = $this->getAttributesOptions()
            ->getItemById($product->getData($attributeCode));

        return $option ? $option->getValue() : false;
    }

    public function getCustomName($name){
        $customName = explode(" - ", $name);
        return $customName[1];
    }

    /**
     * @param Mage_Catalog_Model_Category
     * @return string
     */
    public function _getCategoryAttributeSetCode($category)
    {
        if (!isset($this->_attributeSets[$category->getData('related_product_attribute_set')])) {
            /** @var Mage_Eav_Model_Resource_Entity_Attribute_Set_Collection $sets */
            $sets = Mage::getResourceModel('eav/entity_attribute_set_collection');
            $sets->addFieldToFilter('attribute_set_id', ['eq' => $category->getData('related_product_attribute_set')]);
            $sets->setPageSize(1);

            $this->_attributeSets[$category->getData('related_product_attribute_set')] = $sets->getFirstItem()->getData('attribute_set_code');
        }

        return $this->_attributeSets[$category->getData('related_product_attribute_set')];
    }

}
