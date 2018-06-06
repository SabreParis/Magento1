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
class Ayaline_DataflowManager_Model_Import_Catalog_Product extends Ayaline_DataflowManager_Model_Import_Catalog_Abstract
{

    const PRODUCT_VISIBILITY_CATALOG = 'catalog';
    const PRODUCT_VISIBILITY_SEARCH = 'search';
    const PRODUCT_VISIBILITY_BOTH = 'both';
    const PRODUCT_VISIBILITY_NONE = 'none';

    protected $_sendEmail = false;
    protected $_canArchive = false;
    protected $_moveImage = false;

    protected $_xmlElementClassName = 'Ayaline_DataflowManager_Model_SimpleXml_Element';

    /**
     * Flag to avoid entity type id change
     *
     * @var bool
     */
    protected $_secureEntityType = true;

    /**
     * Flag to avoid attribute set id change
     *
     * @var bool
     */
    protected $_secureAttributeSetId = true;

    protected $_productEntityTypeId;
    protected $_productTypes;
    protected $_attributeSets;
    protected $_attributesBySet = array();

    /**
     * @var Ayaline_DataflowManager_Model_Resource_Catalog_Product
     */
    protected $_productResource;

    /**
     * @var Ayaline_DataflowManager_Model_Resource_Catalog_Attribute
     */
    protected $_attributeResource;

    /**
     * @var Mage_Catalog_Model_Product_Attribute_Backend_Media
     */
    protected $_mediaGalleryAttributeBackend;

    /**
     * @var Mage_Catalog_Model_Product_Url
     */
    protected $_productModelUrl;

    /**
     * @var Ayaline_DataflowManager_Model_SimpleXml_Element
     */
    protected $_currentProductXml;

    /**
     * @var Ayaline_DataflowManager_Model_Catalog_Product
     */
    protected $_currentProduct;

    /**
     * @var Mage_Core_Model_Website
     */
    protected $_currentWebsite;

    /**
     * @var Mage_Core_Model_Store
     */
    protected $_currentStore;

    /**
     * @var string
     */
    protected $_currentCacheHash;

    protected $_images = array();
    protected $_categoryCacheTags = array();
    protected $_productCacheTags = array();

    protected $_productCount = 0;

    protected $_profileProduct = false;

//    protected function _beforeImport($filename)
//    {
//        parent::_beforeImport($filename);
//
//        // validate file XSD
//
//        return $this;
//    }

    /**
     * {@inheritdoc}
     */
    protected function _beforeExecuteScript()
    {
        parent::_beforeExecuteScript();

        $this->_productTypes = Mage_Catalog_Model_Product_Type::getTypes();
        $this->_productEntityTypeId = Mage::getSingleton('eav/config')
                                          ->getEntityType(Mage_Catalog_Model_Product::ENTITY)
                                          ->getId();
        $this->_attributeSets = Mage::getResourceSingleton('eav/entity_attribute_set_collection')
                                    ->setEntityTypeFilter($this->_productEntityTypeId)
                                    ->toOptionHash();

        $this->_attributeResource = Mage::getResourceSingleton('ayaline_dataflowmanager/catalog_attribute');
        $this->_productResource = Mage::getResourceSingleton('ayaline_dataflowmanager/catalog_product');
        $this->_productModelUrl = Mage::getSingleton('catalog/factory')->getProductUrlInstance();

        $this->_moveImage = (bool)$this->_getArgument('move_image', false);

        $this->_profileProduct = (bool)$this->_getArgument('profile_product', false);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _import($filename)
    {
        Mage::helper('ayaline_dataflowmanager/xml')->read(
            $filename,
            '<product uniq_id',
            '</product>',
            array($this, 'processProduct'),
            array(),
            false
        );

        $this->_cleanCache();
    }

    /**
     * {@inheritdoc}
     */
    protected function _getDocumentation()
    {
        $doc = <<<DOC
aYaline Catalog Product Import

Optional arguments:
--move_image        Copy/move product image (default: copy - 1: move)
--profile_product   Start profiling for each product individually (default: no - 1: yes)
DOC;

        return $doc;
    }

    /**
     * {@inheritdoc}
     */
    protected function _validate()
    {
        // TODO: Implement _validate() method.
        return true;
    }

    protected function _clearData()
    {
        $this->_productCount = 0;
        $this->_currentProduct = null;
        $this->_currentProductXml = null;
        $this->_currentCacheHash = '';
        $this->_productCacheTags = array();
        $this->_categoryCacheTags = array();
    }

    /**
     * Try to load product from SKU
     *
     * @throws Mage_Core_Exception
     */
    protected function _loadProduct()
    {
        $this->_currentProduct = null;
        $this->_currentCacheHash = '';

        $product = Mage::getModel('ayaline_dataflowmanager/catalog_product');

        $this->_log("Find SKU to load product");
        $productId = 0;
        $uniqId = $this->_currentProductXml->getValueFromXpath('@uniq_id');
        $merchant = $this->_currentProductXml->getValueFromXpath('identifiers/merchant');

        // first: try via uniq_id
        if ($uniqId !== null) {
            $this->_log(" UniqId is {$uniqId}");
            $productId = Mage::getResourceSingleton('ayaline_dataflowmanager/catalog_product')->getIdByUniqId($uniqId);
        } else {
            // generate uniq_id here?
            $uniqId = null;
        }

        // if we found merchant
        if ($merchant !== null) {
            // uniq_id doesn't exists or no product with this uniq_id
            if (!$productId) {
                $this->_log(" SKU is {$merchant}");
                $productId = $product->getIdBySku($merchant);
            }
        } else {
            Mage::throwException("Can't find SKU in XML");
        }


        if ($productId) {
            $this->_currentCacheHash = $this->_canProcessObject($productId, $this->_currentProductXml->saveXML());

            $this->_log(" Product exists so load it ({$productId})");
            $product->load($productId);
        } else {
            $this->_log(" Product doesn't exists so create a new one");
            $product->setData('sku', $merchant);
            $product->setData('ayaline_uniq_id', $uniqId);
            $product->setData('status', Mage_Catalog_Model_Product_Status::STATUS_DISABLED);
            $product->setData('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE);

            $this->_currentCacheHash = $this->_generateCacheHash($this->_currentProductXml->saveXML());
        }

        $this->_currentProduct = $product;
    }

    protected function _initProduct()
    {
        $this->_log("\tInit product information");
        $productType = $this->_currentProductXml->getValueFromXpath('product_type');
        $attributeSet = $this->_currentProductXml->getValueFromXpath('attribute_set');

        if ($productType === null || $attributeSet === null) {
            Mage::throwException("Can't find product type or attribute set in XML");
        }

        // check product type
        if (!array_key_exists($productType, $this->_productTypes)) {
            Mage::throwException("Invalid product type: {$productType}");
        }

        // check attribute set
        $attributeSetId = array_search($attributeSet, $this->_attributeSets);
        if ($attributeSetId === false) {
            $this->_log("\t Unknown attribute set.", Zend_Log::NOTICE);
            $_eventData = new Varien_Object();
            $_eventData->setData('attribute_set_name', $attributeSet);
            $_eventData->setData('attribute_set_id', $attributeSetId);
            Mage::dispatchEvent(
                'ayaline_dataflow_manager_import_catalog_product_non_existent_attribute_set',
                array('attribute_set_data' => $_eventData)
            );
            $attributeSetId = $_eventData->getData('attribute_set_id');
            if ($attributeSetId === false) {
                Mage::throwException("Invalid attribute set: {$attributeSet}");
            }
            $this->_log("\t  Attribute set found ({$attributeSetId})");
            $this->_addEmailExtraInformation("Attribute set {$attributeSet} ({$attributeSetId}) has been created.");

            $this->_attributeSets[$attributeSetId] = $attributeSet;
        }

        $updatedAt = $this->_currentProductXml->getValueFromXpath('updated_at');
        $updatedAt = ($updatedAt === null) ? Varien_Date::now() : $updatedAt;

        if ($this->_currentProduct->getId()) {
            if ($this->_secureEntityType && $this->_currentProduct->getTypeId() != $productType) {
                Mage::throwException("Product type id mismatch (db: {$this->_currentProduct->getTypeId()} ; XML: {$productType})");
            }

            if ($this->_secureAttributeSetId && $this->_currentProduct->getData('attribute_set_id') != $attributeSetId) {
                Mage::throwException("Product attribute set id mismatch (db: {$this->_currentProduct->getData('attribute_set_id')} ; XML: {$attributeSet} ({$attributeSetId}))");
            }
        } else {
            $this->_currentProduct->setData('entity_type_id', $this->_productEntityTypeId);

            $createdAt = $this->_currentProductXml->getValueFromXpath('created_at');
            $createdAt = ($createdAt === null) ? Varien_Date::now() : $createdAt;
            $this->_currentProduct->setData('created_at', $createdAt);
        }

        $attributesBySet = $this->_getAttributesBySet($attributeSetId);
        // init empty values (useful for set value for store only)
        foreach ($attributesBySet as $_attributeCodeBySet => $_attributeBySet) {
            if ($this->_currentProduct->getData($_attributeCodeBySet) !== null) {
                continue;
            }
            $this->_currentProduct->setData($_attributeCodeBySet, null);
        }

        $this->_currentProduct->setData('updated_at', $updatedAt);
        $this->_currentProduct->setData('type_id', $productType);
        $this->_currentProduct->setData('attribute_set_id', $attributeSetId);

        $this->_currentProduct->setUrlModel($this->_productModelUrl);

        $this->_log("\t Product information initialized");
    }

    /**
     * array(
     *  'attribute_code' => array(
     *      'attribute' => Mage_Catalog_Model_Resource_Eav_Attribute,
     *      'options' => null
     *  ),
     *  'attribute_code_2' => array(
     *      'attribute' => Mage_Catalog_Model_Resource_Eav_Attribute,
     *      'options' => array(
     *          array('value' => '', 'label' => ''),
     *      )
     *  ),
     *  ...
     * )
     *
     * @param $attributeSetId
     * @return array
     */
    protected function _getAttributesBySet($attributeSetId)
    {
        if (!array_key_exists($attributeSetId, $this->_attributesBySet)) {
            $this->_startProfiling(__FUNCTION__);
            $this->_attributesBySet[$attributeSetId] = $this->_attributeResource
                ->getAttributesByAttributeSetId($this->_productEntityTypeId, $attributeSetId);
            $this->_stopProfiling(__FUNCTION__);
        }

        return $this->_attributesBySet[$attributeSetId];
    }

    protected function _initDefaultAttributes()
    {
        $this->_log("\tInit default attributes");
        $defaultAttributesNode = $this->_currentProductXml->searchXpath('default_attributes', true);
        if ($defaultAttributesNode !== null) {
            $attributesBySet = $this->_getAttributesBySet($this->_currentProduct->getData('attribute_set_id'));
            /** @var Ayaline_DataflowManager_Model_SimpleXml_Element $_attributeNode */
            foreach ($defaultAttributesNode->children() as $_attributeNode) {
                $_attributeCode = $_attributeNode->getAttribute('code');
                if (array_key_exists($_attributeCode, $attributesBySet)) {
                    $_attributeValue = $_attributeNode->__toString();
                    $_attributeOptionId = $_attributeNode->getAttribute('option_id');
                    $this->_log("\t Process attribute {$_attributeCode}:");
                    $this->_log("\t  - Value: {$_attributeValue}");
                    $this->_log("\t  - Option Id: {$_attributeOptionId}");
                    /** @var Mage_Catalog_Model_Resource_Eav_Attribute $_attribute */
                    $_attribute = $attributesBySet[$_attributeCode]['attribute'];

                    if ($attributesBySet[$_attributeCode]['options'] !== null) {
                        $_attributeOptionId = $this->_getOptionId($attributesBySet[$_attributeCode]['options'], $_attributeValue, $_attributeOptionId, $_attribute);

                        $this->_currentProduct->setData($_attributeCode, $_attributeOptionId);
                    } else {
                        $this->_currentProduct->setData($_attributeCode, $_attributeValue);
                    }
                    // @todo: manage mutiselect (maybe some changes on flow)
                    // @todo: dispatchEvent for each attribute (eg: manage custom source model logic)

//                    Mage::dispatchEvent(
//                        'ayaline_dataflow_manager_init_default_attributes',
//                        array(
//                            'attribute_code'        => $_attributeCode,
//                            'attribute_value'       => $_attributeValue,
//                            'attribute_option_id'   => $_attributeOptionId,
//                            'attribute_node'        => $_attributeNode,
//                            'attribute'             => $_attribute,
//                        )
//                    );

                } else {
                    $this->_log("\t Attribute {$_attributeCode} is not available for this attribute set", Zend_Log::NOTICE);
                }
            }
        }

        $this->_log("\t Default attributes initialized");
    }

    protected function _initDefaultUrlKey()
    {
        $this->_log("\tInit default URL key");

        // init url_key here instead of backend attribute, due to weird performance issue...
        $urlKey = $this->_currentProduct->getData('url_key');
        if (!$urlKey) {
            $urlKey = $this->_currentProduct->getName();
        }
        $this->_currentProduct->setData('url_key', $this->_productModelUrl->formatUrlKey($urlKey));

        $this->_log("\t Default URL key initialized ({$this->_currentProduct->getData('url_key')})");
    }

    /**
     * @param Ayaline_DataflowManager_Model_SimpleXml_Element $storeNode
     * @param int                                             $storeId
     */
    protected function _setAttributesValues($storeNode, $storeId)
    {
        $this->_log("\t Set attributes values");
        $storeAttributesNode = $storeNode->searchXpath('attributes', true);
        if ($storeAttributesNode !== null) {
            $productId = $this->_currentProduct->getId();

            $attributesBySet = $this->_getAttributesBySet($this->_currentProduct->getData('attribute_set_id'));
            /** @var Ayaline_DataflowManager_Model_SimpleXml_Element $_storeAttributeNode */
            foreach ($storeAttributesNode->children() as $_storeAttributeNode) {
                $_storeAttributeCode = $_storeAttributeNode->getAttribute('code');
                if (array_key_exists($_storeAttributeCode, $attributesBySet)) {
                    $_storeAttributeValue = $_storeAttributeNode->__toString();
                    $_storeAttributeOptionId = $_storeAttributeNode->getAttribute('option_id');
                    $this->_log("\t\t Process attribute {$_storeAttributeCode}:");
                    $this->_log("\t\t  - Value: {$_storeAttributeValue}");
                    $this->_log("\t\t  - Option Id: {$_storeAttributeOptionId}");

                    /** @var Mage_Catalog_Model_Resource_Eav_Attribute $_attribute */
                    $_attribute = $attributesBySet[$_storeAttributeCode]['attribute'];

                    if ($attributesBySet[$_storeAttributeCode]['options'] !== null) {
                        $_storeAttributeOptionId = $this->_getOptionId($attributesBySet[$_storeAttributeCode]['options'], $_storeAttributeValue, $_storeAttributeOptionId, $_attribute);

                        $this->_productResource->setAttributeValueForSave($_attribute, $_storeAttributeOptionId, $productId, $storeId);
                    } else {
                        $this->_productResource->setAttributeValueForSave($_attribute, $_storeAttributeValue, $productId, $storeId);
                    }
                } else {
                    $this->_log("\t\t Attribute {$_storeAttributeCode} is not available for this attribute set", Zend_Log::NOTICE);
                }
            }
        } else {
            $this->_log("\t  No attributes node");
        }
    }

    /**
     * @param array                                     $options
     * @param string                                    $attributeValue
     * @param int|null                                  $attributeOptionId
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * @return string
     */
    protected function _getOptionId($options, $attributeValue, $attributeOptionId, $attribute)
    {
        if (count($options)) {
            foreach ($options as $_option) {
                if (strcasecmp($_option['label'], $attributeValue) == 0
                    || $_option['value'] === $attributeValue
                    || $_option['value'] === $attributeOptionId
                ) {
                    $attributeOptionId = $_option['value'];
                    break;
                }
            }

            if ($attributeOptionId === null) { // no option found
                $attributeOptionId = $this->_attributeResource->addAttributeOption($attribute->getId(), $attributeValue);
                $this->_attributesBySet[$this->_currentProduct->getData('attribute_set_id')][$attribute->getAttributeCode()]['options'][] = array(
                    'label' => $attributeValue,
                    'value' => $attributeOptionId,
                );
            }

        } else {
            // no options yet, so add new one
            $attributeOptionId = $this->_attributeResource->addAttributeOption($attribute->getId(), $attributeValue);
            $this->_attributesBySet[$this->_currentProduct->getData('attribute_set_id')][$attribute->getAttributeCode()]['options'][] = array(
                'label' => $attributeValue,
                'value' => $attributeOptionId,
            );
        }

        return $attributeOptionId;
    }

    /**
     * @param Ayaline_DataflowManager_Model_SimpleXml_Element $websiteNode
     * @return bool|int
     */
    protected function _processWebsiteData($websiteNode)
    {
        $separator = str_repeat('_', strlen(__METHOD__));
        $this->_log($separator);
        $this->_log(__METHOD__);
        $this->_startProfiling(__FUNCTION__);

        try {
            $this->_currentWebsite = null;
            $websiteIdentifier = $websiteNode->getAttribute('identifier');
            if ($websiteIdentifier === null) {
                Mage::throwException("Can't find website identifier.");
            }

            $website = Mage::app()->getWebsite($websiteIdentifier);
            if (!$website->getId()) {
                Mage::throwException("Can't find website for this identifier ({$websiteIdentifier}).");
            }
            $this->_currentWebsite = $website;
            $websiteId = $website->getId();

            $this->_log("\tWebsite {$website->getCode()} has been found");

            $enabled = $websiteNode->getValueFromXpath('enabled', false);
            if ($enabled !== false) {
                $this->_log("\tUpdate product status to {$enabled}");
                $this->_currentProduct->addAttributeUpdate(
                    'status',
                    $enabled === '0' ? Mage_Catalog_Model_Product_Status::STATUS_DISABLED : Mage_Catalog_Model_Product_Status::STATUS_ENABLED,
                    $website->getDefaultStore()->getId()
                );
            }

            $this->_log("\tBefore process language");
            $language = $websiteNode->searchXpath('language');
            if ($language !== null && count($language)) {
                /** @var Ayaline_DataflowManager_Model_SimpleXml_Element $_language */
                foreach ($language as $_language) {
                    $this->_currentStore = null;
                    try {
                        $_locale = $_language->getAttribute('idref');
                        if ($_locale === null) {
                            Mage::throwException('Missing locale code');
                        }
                        $_store = Mage::helper('ayaline_dataflowmanager/catalog')->getStoreByWebsiteAndLocale($website->getId(), $_locale);
                        if ($_store === null) {
                            Mage::throwException("Can't find store for website {$website->getId()} and locale {$_locale}");
                        }

                        $_storeNode = $this->_currentProductXml->searchXpath("languages/language[@id='{$_locale}']", true);
                        if ($_storeNode === null) {
                            Mage::throwException("Can't find store data for locale {$_locale}");
                        }
                        $this->_currentStore = $_store;
                        $this->_log("\t Store {$_store->getCode()} has been found");
                        $this->_processStoreData($_storeNode);

                    } catch (Exception $e) {
                        $this->_log("\t {$e->getMessage()}", Zend_Log::ERR);
                    }
                }
            } else {
                Mage::throwException("Language section doesn't exists...");
            }
        } catch (Mage_Core_Exception $e) {
            $this->_log("\t{$e->getMessage()}", Zend_Log::ERR);
            $websiteId = false;
        }

        $this->_log($separator . "\n");
        $this->_stopProfiling(__FUNCTION__);

        return $websiteId;
    }

    /**
     * @param Ayaline_DataflowManager_Model_SimpleXml_Element $storeNode
     * @return $this
     */
    protected function _processStoreData($storeNode)
    {
        $this->_startProfiling(__FUNCTION__);
        $separator = str_repeat('=', strlen(__METHOD__));
        $this->_log("\t{$separator}");
        $this->_log("\t" . __METHOD__);

        $storeId = $this->_currentStore->getId();
        try {
            $this->_setMarketingData($storeNode, $storeId);
            $this->_setVisibility($storeNode, $storeId);
            $this->_setAttributesValues($storeNode, $storeId);
            $this->_setMediaGallery($storeNode, $storeId);

            $this->_log("\tSave attributes values");
            $this->_productResource->processAttributeValues();
            $this->_log("\t Attributes values saved");
        } catch (Mage_Core_Exception $e) {
            $this->_log("\t{$e->getMessage()}", Zend_Log::ERR);
        }

        $this->_log("\t{$separator}\n");
        $this->_stopProfiling(__FUNCTION__);

        return $this;
    }

    /**
     * @param Ayaline_DataflowManager_Model_SimpleXml_Element $storeNode
     * @param int                                             $storeId
     * @throws Mage_Core_Exception
     */
    protected function _setMarketingData($storeNode, $storeId)
    {
        $this->_log("\t Set marketing attribute");
        $name = $storeNode->getValueFromXpath('name');
        $description = $storeNode->getValueFromXpath('description');
        $shortDescription = $storeNode->getValueFromXpath('short_description');

        if ($name === null || $description === null || $shortDescription === null) {
            Mage::throwException("Missing name, description or short description.");
        }

        $marketingData = array(
            'name'              => $name,
            'description'       => $description,
            'short_description' => $shortDescription,
        );

        $urlKey = $storeNode->getValueFromXpath('url_key');
        if ($urlKey === null) {
            $urlKey = $name;
        }
        $urlKey = $this->_currentProduct->formatUrlKey($urlKey);
        $marketingData['url_key'] = $urlKey;

        $marketingData['meta_title'] = $storeNode->getValueFromXpath('meta/title');
        $marketingData['meta_description'] = $storeNode->getValueFromXpath('meta/description');
        $marketingData['meta_keyword'] = $storeNode->getValueFromXpath('meta/keywords');

        $attributesBySet = $this->_getAttributesBySet($this->_currentProduct->getData('attribute_set_id'));
        $productId = $this->_currentProduct->getId();
        foreach ($marketingData as $_attributeCode => $_attributeValue) {
            $this->_log("\t\tSet {$_attributeCode} attribute: {$_attributeValue}");
            $this->_productResource->setAttributeValueForSave($attributesBySet[$_attributeCode]['attribute'], $_attributeValue, $productId, $storeId);
        }
    }

    /**
     * @param Ayaline_DataflowManager_Model_SimpleXml_Element $storeNode
     * @param int                                             $storeId
     * @throws Mage_Core_Exception
     */
    protected function _setVisibility($storeNode, $storeId)
    {
        $this->_log("\t Set visibility");
        $flowVisibility = $storeNode->getValueFromXpath('visibility');
        $visibility = Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE;
        $visibilityTxt = 'VISIBILITY_NOT_VISIBLE';
        if ($flowVisibility === self::PRODUCT_VISIBILITY_CATALOG) {
            $visibility = Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG;
            $visibilityTxt = 'VISIBILITY_IN_CATALOG';
        } elseif ($flowVisibility === self::PRODUCT_VISIBILITY_SEARCH) {
            $visibility = Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH;
            $visibilityTxt = 'PRODUCT_VISIBILITY_SEARCH';
        } elseif ($flowVisibility === self::PRODUCT_VISIBILITY_BOTH) {
            $visibility = Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH;
            $visibilityTxt = 'VISIBILITY_BOTH';
        }

        $attributesBySet = $this->_getAttributesBySet($this->_currentProduct->getData('attribute_set_id'));
        $this->_log("\t  '{$flowVisibility}' to {$visibilityTxt} ({$visibility})");
        $this->_productResource->setAttributeValueForSave($attributesBySet['visibility']['attribute'], $visibility, $this->_currentProduct->getId(), $storeId);
    }

    /**
     * @param Ayaline_DataflowManager_Model_SimpleXml_Element $storeNode
     * @param int                                             $storeId
     * @throws Mage_Core_Exception
     */
    protected function _setMediaGallery($storeNode, $storeId)
    {
        $this->_log("\t Set media gallery");
        $storeMediaGalleryNodes = $storeNode->searchXpath('media_gallery/gallery_item');
        if ($storeMediaGalleryNodes !== null && count($storeMediaGalleryNodes)) {

            $productId = $this->_currentProduct->getId();
            $attributeId = $this->_mediaGalleryAttributeBackend->getAttribute()->getId();
            /** @var Mage_Catalog_Model_Resource_Product_Attribute_Backend_Media $mediaGalleryAttributeBackendResource */
            $mediaGalleryAttributeBackendResource = Mage::getResourceSingleton('catalog/product_attribute_backend_media');

            $attributesBySet = $this->_getAttributesBySet($this->_currentProduct->getData('attribute_set_id'));

            foreach ($storeMediaGalleryNodes as $_galleryItemNode) {
                try {
                    $_imageNodeRef = $_galleryItemNode->getValueFromXpath('image/@ref');
                    if ($_imageNodeRef === null) {
                        Mage::throwException("Gallery item have no ref");
                    }
                    if (!array_key_exists($_imageNodeRef, $this->_images)) {
                        Mage::throwException("No image for {$_imageNodeRef}");
                    }

                    $_galleryData = array(
                        'entity_id'    => $productId,
                        'attribute_id' => $attributeId,
                        'value'        => $this->_images[$_imageNodeRef],
                    );
                    $this->_log("\t\tInsert gallery: " . Mage::helper('core')->jsonEncode($_galleryData));
                    $_galleryValueId = $mediaGalleryAttributeBackendResource->insertGallery($_galleryData);

                    $_galleryValueInStoreData = array(
                        'value_id' => $_galleryValueId,
                        'label'    => $_galleryItemNode->getValueFromXpath('label', ''),
                        'position' => $_galleryItemNode->getValueFromXpath('position', 0),
                        'disabled' => $_galleryItemNode->getValueFromXpath('disabled', 0),
                        'store_id' => $storeId,
                    );
                    $this->_log("\t\tInsert gallery value: " . Mage::helper('core')->jsonEncode($_galleryValueInStoreData));
                    $mediaGalleryAttributeBackendResource->insertGalleryValueInStore($_galleryValueInStoreData);

                    if ($_type = $_galleryItemNode->getValueFromXpath('type', false)) {
                        $this->_log("\t\t Process type: {$_type}");
                        $_imageTypes = explode(',', $_type);

                        foreach ($_imageTypes as $_imageType) {
                            if (array_key_exists($_imageType, $attributesBySet) && array_key_exists("{$_imageType}_label", $attributesBySet)) {
                                $this->_productResource->setAttributeValueForSave(
                                    $attributesBySet[$_imageType]['attribute'],
                                    $this->_images[$_imageNodeRef],
                                    $productId,
                                    $storeId
                                );
                                $this->_productResource->setAttributeValueForSave(
                                    $attributesBySet["{$_imageType}_label"]['attribute'],
                                    $_galleryValueInStoreData['label'],
                                    $productId,
                                    $storeId
                                );
                            } else {
                                $this->_log("\t\t  Attribute {$_type} is not available for this attribute set", Zend_Log::NOTICE);
                            }
                        }
                    }
                } catch (Mage_Core_Exception $e) {
                    $this->_log("\t   {$e->getMessage()}", Zend_Log::ERR);
                }
            }
        } else {
            $this->_log("\t  No media gallery node");
        }
    }

    protected function _downloadImages()
    {
        $this->_log("\tDownload Images");

        $this->_images = array();

        $attributesBySet = $this->_getAttributesBySet($this->_currentProduct->getData('attribute_set_id'));
        if (isset($attributesBySet['media_gallery'])) {
            $this->_mediaGalleryAttributeBackend = $attributesBySet['media_gallery']['attribute']->getBackend();
            $this->_mediaGalleryAttributeBackend->setAttribute($attributesBySet['media_gallery']['attribute']);

            $imagesNode = $this->_currentProductXml->searchXpath('images', true);
            if ($imagesNode !== null) {

                if ($imagesNode->count()) {
                    $this->_log("\t  Clean current images");
                    $mediaGalleryData = $this->_currentProduct->getData('media_gallery');
                    if (isset($mediaGalleryData['images']) && is_array($mediaGalleryData['images'])) {
                        foreach ($mediaGalleryData['images'] as &$_imageData) {
                            $this->_log("\t   Remove image {$_imageData['file']}");
                            $_imageData['removed'] = 1;
                        }
                    }
                    $this->_currentProduct->setData('media_gallery', $mediaGalleryData);
                }

                $imageMethodName = 'getImageVia' . ucfirst(Mage::getSingleton('ayaline_dataflowmanager/system_config')->getImportImageSource('product'));

                /** @var Ayaline_DataflowManager_Model_SimpleXml_Element $_imageNode */
                foreach ($imagesNode->children() as $_imageNode) {
                    try {
                        $_imageNodeId = $_imageNode->getAttribute('id');
                        if ($_imageNodeId === null) {
                            Mage::throwException("Image have no image id");
                        }

                        $_source = $_imageNode->src->__toString();

                        if ($_imageFile = Mage::helper('ayaline_dataflowmanager/image')->$imageMethodName($_source, 'product')) {
                            $_addedImage = $this->_mediaGalleryAttributeBackend->addImage(
                                $this->_currentProduct,
                                $_imageFile,
                                null,
                                $this->_moveImage,
                                false
                            );
                            $this->_log("\t  Image imported to {$_addedImage}");
                            $this->_images[$_imageNodeId] = $_addedImage;
                        } else {
                            Mage::throwException("Can't get image from {$_source}");
                        }
                    } catch (Mage_Core_Exception $e) {
                        $this->_log("\t  {$e->getMessage()}", Zend_Log::ERR);
                    }
                }
            } else {
                $this->_log("\t No images node", Zend_Log::NOTICE);
            }
        } else {
            $this->_log("\t Can't find attribute media_gallery for this attribute set", Zend_Log::ERR);
        }
    }

    protected function _cleanCache()
    {
        $separator = str_repeat('~', strlen(__METHOD__));
        $this->_log($separator);
        $this->_log(__METHOD__);
        $this->_startProfiling(__FUNCTION__);

        if (count($this->_productCacheTags)) {
            $this->_log(' Clean ' . count($this->_productCacheTags) . ' product cache tags');
            Mage::app()->cleanCache(array_keys($this->_productCacheTags));
            $this->_log('  Cleaned');
        }

        if (count($this->_categoryCacheTags)) {
            $this->_log(' Clean ' . count($this->_categoryCacheTags) . ' category cache tags');
            Mage::app()->cleanCache(array_keys($this->_categoryCacheTags));
            $this->_log('  Cleaned');
        }

        $this->_log("{$separator}\n");
        $this->_stopProfiling(__FUNCTION__);
    }

    protected function _saveProduct()
    {
        $this->_log('  Before save product');
        Mage::dispatchEvent(
            'ayaline_dataflow_manager_import_catalog_product_before_save',
            array(
                'product_xml'        => $this->_currentProductXml,
                'product'            => $this->_currentProduct,
                'product_resource'   => $this->_productResource,
                'attribute_resource' => $this->_attributeResource,
            )
        );

        $this->_log(' Save product');
        $this->_currentProduct->save();
        $this->_log("  Product saved ({$this->_currentProduct->getId()})");

        $this->_log('  After save product');
        Mage::dispatchEvent(
            'ayaline_dataflow_manager_import_catalog_product_after_save',
            array(
                'product_xml'        => $this->_currentProductXml,
                'product'            => $this->_currentProduct,
                'product_resource'   => $this->_productResource,
                'attribute_resource' => $this->_attributeResource,
            )
        );

        $this->_productCacheTags[Mage_Catalog_Model_Product::CACHE_TAG . "_{$this->_currentProduct->getId()}"] = true;

        $this->_categoryCacheTags = $this->_currentProduct->getCategoryCacheTags($this->_categoryCacheTags);
    }

    /**
     * @param string $nodeAsString
     * @param array  $args
     * @return bool
     */
    public function processProduct($nodeAsString, $args = array())
    {
        $this->_productCount++;
        $return = false;
        $timerName = ($this->_profileProduct !== false) ? __FUNCTION__ . $this->_productCount : __FUNCTION__;
        $this->_startProfiling($timerName);
        $separator = str_repeat('-', strlen(__METHOD__));

        try {
            $this->_currentProductXml = null;
            $this->_log($separator);
            $this->_log(__METHOD__);
            $this->_log(" Product number {$this->_productCount}");

            // xml & handle errors
            $productXml = simplexml_load_string($nodeAsString, $this->_xmlElementClassName);
            if ($productXml === false) {
                $this->_log($nodeAsString);
                $messages = array('Failed loading XML');
                foreach (libxml_get_errors() as $_error) {
                    $messages[] = "\t{$_error->message}";
                }

                Mage::throwException(implode("\n", $messages));
            }

            $this->_currentProductXml = $productXml;
            unset($productXml);

            $this->_loadProduct();
            $this->_initProduct();
            $this->_initDefaultAttributes();
            $this->_initDefaultUrlKey();
            $this->_downloadImages();

            $this->_saveProduct();

            $websites = $this->_currentProductXml->searchXpath('websites', true);
            $websiteIds = array();
            if ($websites !== null) {
                foreach ($websites as $_website) {
                    if ($_websiteId = $this->_processWebsiteData($_website)) {
                        $websiteIds[] = $_websiteId;
                    }
                }
            }

            $this->_log(' Save product/website association (' . implode(', ', $websiteIds) . ')');
            $this->_currentProduct->setData('website_ids', $websiteIds);
            $this->_productResource->saveWebsiteIds($this->_currentProduct);
            $this->_log('  Product/website association saved');


            Mage::dispatchEvent(
                'ayaline_dataflow_manager_import_catalog_product_before_end',
                array(
                    'product_xml'        => $this->_currentProductXml,
                    'product'            => $this->_currentProduct,
                    'product_resource'   => $this->_productResource,
                    'attribute_resource' => $this->_attributeResource,
                )
            );

            $this->_updateCacheHash($this->_currentProduct->getId(), $this->_currentCacheHash);

            $return = true;

        } catch (Mage_Core_Exception $e) {
            if ($e->getCode() === self::IMPORT_CACHE_EXISTS_EXCEPTION_CODE) {
                $this->_log($e->getMessage());
            } else {
                $this->_log($e->getMessage(), Zend_Log::ERR);
            }
        } catch (Exception $e) {
            $this->_log($e->getMessage(), Zend_Log::ERR);
            $this->_logException($e);
        }

        $this->_log($separator . "\n");
        $this->_stopProfiling($timerName);

        return $return;
    }

}
