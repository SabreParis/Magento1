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
class Ayaline_DataflowManager_Model_Catalog_Product extends Mage_Catalog_Model_Product
{

    protected $_categoryCacheTags = array();

    protected function _construct()
    {
        parent::_construct();
        $this->_init('ayaline_dataflowmanager/catalog_product');
    }

    /**
     * {@inheritdoc}
     *
     * Do nothing, import will purge it later
     */
    public function cleanModelCache()
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * Do nothing, import will purge it later
     */
    public function cleanCache()
    {
        return $this;
    }

    /**
     * Return category cache tags
     *
     * @param $tags array
     * @return array
     */
    public function getCategoryCacheTags($tags)
    {
        if ($origTags = $this->getCacheTags()) {
            foreach ($origTags as $_origTag) {
                $tags[$_origTag] = true;
            }
        }

        $affectedCategoryIds = $this->_getResource()->getCategoryIdsWithAnchors($this);
        foreach ($affectedCategoryIds as $_categoryId) {
            $tags[Mage_Catalog_Model_Category::CACHE_TAG . '_' . $_categoryId] = true;
        }

        return $tags;
    }

    /**
     * @param Mage_Catalog_Model_Product_Url $urlModel
     * @return $this
     */
    public function setUrlModel($urlModel)
    {
        $this->_urlModel = $urlModel;

        return $this;
    }

}
