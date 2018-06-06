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
class Ayaline_DataflowManager_Model_Utils_Io_Ftp extends Varien_Io_Ftp
{

    /**
     * Write a file from string, file or stream
     * 
     * 
     * @override Adding test by file size, comparing local and remote file
     * 
     * @param string $filename
     * @param string|resource $src filename, string data or source stream
     * @return int|boolean
     */
    public function write($filename, $src, $mode = null)
    {
        $result = parent::write($filename, $src, $mode);

        if (!$result) {
            return $result;
        }
        
        // Add test by file size        
        $localFileSize = filesize($src);
        $remoteFileSize = ftp_size($this->_conn, $filename);

        if ($localFileSize === $remoteFileSize) {
            return $result;
        }

        return false;
    }
}
