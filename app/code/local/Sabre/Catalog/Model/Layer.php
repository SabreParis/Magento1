<?php

/**
 * created: 2015
 *
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Catalog_Model_Layer extends Mage_Catalog_Model_Layer
{

    /**
     * {@inheritdoc}
     */
    protected function _getSetIds()
    {
        $key = $this->getStateKey().'_SET_IDS';
        $setIds = $this->getAggregator()->getCacheData($key);

        if ($setIds === null) {
            if (Mage::registry('current_category')) {
                // get only filterable attributes linked to current category
                $setIds = [Mage::registry('current_category')->getData('related_product_attribute_set')];
            } else {
                $setIds = $this->getProductCollection()->getSetIds();
            }

            $this->getAggregator()->saveCacheData($setIds, $key, $this->getStateTags());
        }

        return $setIds;
    }

}
