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
class Ayaline_DataflowManager_Model_System_Config_Source_Transport_Ftp_FileMode
{

    public function toOptionArray()
    {
        return array(
            array('value' => FTP_BINARY, 'label' => Mage::helper('ayaline_dataflowmanager')->__('Binary')),
            array('value' => FTP_ASCII, 'label' => Mage::helper('ayaline_dataflowmanager')->__('Ascii')),
        );
    }
}
