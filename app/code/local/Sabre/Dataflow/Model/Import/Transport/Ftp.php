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
class Sabre_Dataflow_Model_Import_Transport_Ftp extends Ayaline_DataflowManager_Model_Export_Transport_Ftp
{

    /**
 * @param $filename
 * @param $destination
 * @return boolean
 */
    public function read($filename, $destination) {

        /* @var $io Ayaline_DataflowManager_Model_Utils_Io_Ftp */
        $io = $this->_io;

        $result = $io->read($filename, $destination);

        return $result;

    }

    public function rm($file) {
        /* @var $io Ayaline_DataflowManager_Model_Utils_Io_Ftp */
        $io = $this->_io;
        $io->rm($file);
    }

    /**
     * @param $dir
     * @param $grep
     * @return Array
     *
     * List elements of directory
     */
    public function ls($filterCallbackFunction=null) {

        /* @var $io Ayaline_DataflowManager_Model_Utils_Io_Ftp */
        $io = $this->_io;
        $result = $io->ls();
        if ($filterCallbackFunction) {
            $result = array_filter($result, $filterCallbackFunction);
        }
        return $result;
    }

}
