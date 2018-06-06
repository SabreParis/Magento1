<?php
/**
 * created : 2014
 * 
 * @category Ayaline
 * @package Ayaline_XXXX
 * @author aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
 

class Ayaline_DataflowManager_Model_Config
{

    const DATAFLOW_CACHE_TAG = 'AYALINE_DATAFLOW_MANAGER';

    /**
     * Key for cache
     */
    const DATAFLOW_CONFIG_KEY = 'ayaline_dataflowmanager_config';

    /**
     * File name
     */
    const DATAFLOW_CONFIG_FILENAME = 'dataflow.xml';

    /**
     * Config
     *
     * @var Varien_Simplexml_Config
     */
    protected $_config = null;

    /**
     * Load config from files and try to cache it
     *
     * @return Varien_Simplexml_Config
     */
    protected function _getXmlConfig()
    {
        if (is_null($this->_config)) {
            $canUsCache = Mage::app()->useCache(strtolower(self::DATAFLOW_CACHE_TAG));
            $cachedXml = Mage::app()->loadCache(self::DATAFLOW_CONFIG_KEY);
            if ($canUsCache && $cachedXml) {
                $xmlConfig = new Varien_Simplexml_Config($cachedXml);
            } else {
                $xmlConfig = new Varien_Simplexml_Config();
                $xmlConfig->loadString('<?xml version="1.0"?><dataflow></dataflow>');
                Mage::getConfig()->loadModulesConfiguration(self::DATAFLOW_CONFIG_FILENAME, $xmlConfig);

                if ($canUsCache) {
                    Mage::app()->saveCache($xmlConfig->getXmlString(), self::DATAFLOW_CONFIG_KEY, array(self::DATAFLOW_CACHE_TAG));
                }
            }
            $this->_config = $xmlConfig;
        }

        return $this->_config;
    }

    /**
     * Return script config
     *
     * @param string $xpath
     * @return SimpleXMLElement | false
     */
    public function getScriptConfig($xpath)
    {
        $config = $this->_getXmlConfig()->getXpath($xpath);

        return is_array($config) ? array_shift($config) : false;
    }

    /**
     * Return config for all scripts
     *
     * @return Varien_Simplexml_Config
     */
    public function getScriptsConfig()
    {
        return $this->_getXmlConfig();
    }

} 