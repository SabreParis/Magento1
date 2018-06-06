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
class Ayaline_DataflowManager_Helper_Catalog extends Ayaline_DataflowManager_Helper_Data
{

    protected $_indexerInitialState = array();

    protected $_stores = array();

    /**
     * @param null|string|array $indexerCodes
     * @return $this
     */
    public function setIndexerToManual($indexerCodes = null)
    {
        $indexers = Mage::getResourceModel('index/process_collection');

        if (is_string($indexerCodes)) {
            $indexers->addFieldToFilter('indexer_code', array('eq' => $indexerCodes));
        } elseif (is_array($indexerCodes)) {
            $indexers->addFieldToFilter('indexer_code', array('in' => $indexerCodes));
        }

        foreach ($indexers as $_indexer) {
            $this->_indexerInitialState[$_indexer->getId()] = $_indexer->getMode();

            $_indexer->setMode(Mage_Index_Model_Process::MODE_MANUAL)->save();
        }


        return $this;
    }

    /**
     * @return $this
     */
    public function restoreIndexerMode()
    {
        if (count($this->_indexerInitialState)) {
            $indexers = Mage::getResourceModel('index/process_collection');
            $indexers->addFieldToFilter('process_id', array('in', array_keys($this->_indexerInitialState)));

            foreach ($indexers as $_indexer) {
                if (array_key_exists($_indexer->getId(), $this->_indexerInitialState)) {
                    $_indexer->setMode($this->_indexerInitialState[$_indexer->getId()])->save();
                }
            }
        }

        $this->_indexerInitialState = array();

        return $this;
    }

    /**
     * @param int    $websiteId
     * @param string $localeCode
     * @return Mage_Core_Model_Store|null
     */
    public function getStoreByWebsiteAndLocale($websiteId, $localeCode)
    {

        if (!count($this->_stores)) {
            $stores = Mage::getResourceModel('core/store_collection')->setLoadDefault(false);
            /** @var Mage_Core_Model_Store $_store */
            foreach ($stores as $_store) {

                if (!array_key_exists($_store->getWebsiteId(), $this->_stores)) {
                    $this->_stores[$_store->getWebsiteId()] = array();
                }

                $_locale = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE, $_store->getId());
                $this->_stores[$_store->getWebsiteId()][$_locale] = $_store;
            }
        }

        return isset($this->_stores[$websiteId][$localeCode]) ? $this->_stores[$websiteId][$localeCode] : null;
    }

}