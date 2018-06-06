<?php

/**
 * Created : 2015
 * 
 * @category Ayaline
 * @package Ayaline_XXXX
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
abstract class Ayaline_DataflowManager_Model_Export_Transport_Abstract
{

    const XML_PATH_CONFIG_TRANSPORT = 'transport/export/{%type%}';
    const XML_VAR_CONFIG_MODEL = 'model';
    const XML_VAR_CONFIG_SYSTEM = 'system_config';

    
    protected $_type;
    
    protected $_io;
    protected $_isOpen;
    protected $_options;
    protected $_config;

    public function __construct()
    {
        if(!$this->_type){
            Mage::throwException("\$_type property must be given");
        }
        $this->_prepareIo();
        $this->_prepareOptions();
    }

    abstract protected function _prepareIo();

    abstract protected function _prepareOptions();

    abstract public function open($path);

    abstract public function close();

    public function getIo()
    {
        return $this->_io;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function getIsOpen()
    {
        return $this->_isOpen;
    }

    public function getType()
    {
        return $this->_type;
    }

    /**
     * 
     * @param string $remoteFile
     * @param string $localFile
     * @param int $retry
     */
    public function write($remoteFile, $localFile, $retry = 3)
    {
        if (!$this->_isOpen) {
            return false;
        }
        for ($i = 0; $i < $retry; $i++) {
            if (($writeResult = $this->_io->write($remoteFile, $localFile))) {

                return $writeResult;
            }
        }

        return false;
    }

    /**
     * 
     * @return SimpleXMLElement
     */
    final public function getConfig($path = null)
    {
        if (!$this->_config) {
            /* @var $dfConfig Ayaline_DataflowManager_Model_Config */
            $dfConfig = Mage::getSingleton('ayaline_dataflowmanager/config');

            $configXpath = trim(str_replace("{%type%}", $this->_type, self::XML_PATH_CONFIG_TRANSPORT), '/');

            if (is_string($path)) {
                $configXpath .= '/' . $path;
            }

            $_config = $dfConfig->getScriptsConfig()->getXpath($configXpath);

            $this->_config = is_array($_config) ? array_shift($_config) : null;
        }

        return $this->_config;
    }

    /**
     * 
     * @return Varien_Simplexml_Element
     */
    final public function getConfigSystem($variableName)
    {
        if (!$this->getConfig()) {

            return null;
        }
        $sysConfig = self::XML_VAR_CONFIG_SYSTEM;

        return ($this->getConfig()->$sysConfig) ? trim((string) $this->getConfig()->$sysConfig->$variableName) : null;
    }
}
