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
class Ayaline_DataflowManager_Model_System_Config_Source_ImageSource
{

    const SOURCE_HTTP = 'http';
    const SOURCE_LOCAL = 'local';

    public function toOptionArray()
    {
        return array(
            array('value' => self::SOURCE_HTTP, 'label' => Mage::helper('ayaline_dataflowmanager')->__('HTTP')),
            array('value' => self::SOURCE_LOCAL, 'label' => Mage::helper('ayaline_dataflowmanager')->__('Local')),
        );
    }

}