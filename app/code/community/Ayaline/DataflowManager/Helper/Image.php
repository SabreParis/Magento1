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
 

class Ayaline_DataflowManager_Helper_Image extends Ayaline_DataflowManager_Helper_Data
{

    /**
     * @param string $source
     * @param string $type
     * @return bool|string
     */
    public function getImageViaLocal($source, $type)
    {
        $basePath = Mage::getSingleton('ayaline_dataflowmanager/system_config')->getImportImageLocalPath($type);
        $source = trim($source, DS);
        $source = $basePath . $source;

        if (file_exists($source) && is_readable($source)) {
            return $source;
        }

        return false;
    }

    /**
     * @param string $source
     * @param string $type
     * @return bool|string
     */
    public function getImageViaHttp($source, $type)
    {
        $baseUrl = Mage::getSingleton('ayaline_dataflowmanager/system_config')->getImportImageHttpUrl($type);
        if (!$baseUrl && strpos($source, 'http') === false) {
            return $this->getImageViaLocal($source, $type);
        }
        $source = $baseUrl . $source;
        $urlPath = parse_url($source, PHP_URL_PATH);
        $urlPath = explode('/', $urlPath);

        $destination = sys_get_temp_dir() . DS . 'magento' . DS . 'import';

        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $destination .= DS . end($urlPath);

        if (copy($source, $destination)) {
            return $destination;
        }

        return false;
    }

}