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
class Ayaline_DataflowManager_Model_Export_Transport_Ftp extends Ayaline_DataflowManager_Model_Export_Transport_Abstract
{

    protected $_type = 'ftp';

    /**
     * 
     * @param string $path Path to remote directory
     * @return boolean
     */
    public function open($path)
    {
        $options = $this->getOptions();
        $options['path'] = $path;

        $this->_isOpen = $this->_io->open($options);

        return $this->_isOpen;
    }

    /**
     * 
     * @return boolean
     */
    public function close()
    {
        if ($this->_isOpen) {
            $this->_isOpen = false;
            return $this->_io->close();
        }
        return true;
    }

    /**
     * 
     * @return \Ayaline_DataflowManager_Model_Export_Transport_Ftp
     */
    protected function _prepareOptions()
    {

        $this->_options = array(
            'host' => Mage::getStoreConfig($this->getConfigSystem('host')),
            'port' => Mage::getStoreConfig($this->getConfigSystem('port')),
            'user' => Mage::getStoreConfig($this->getConfigSystem('user')),
            'password' => Mage::getStoreConfig($this->getConfigSystem('password')),
            'file_mode' => Mage::getStoreConfig($this->getConfigSystem('file_mode')),
            'passive' => Mage::getStoreConfig($this->getConfigSystem('passive')),
            'ssl' => Mage::getStoreConfig($this->getConfigSystem('ssl')),
        );

        return $this;
    }

    /**
     * 
     * @return \Ayaline_DataflowManager_Model_Export_Transport_Ftp
     */
    protected function _prepareIo()
    {
        $this->_io = Mage::getModel('ayaline_dataflowmanager/utils_io_ftp');

        return $this;
    }
}
