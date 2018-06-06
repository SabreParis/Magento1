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
class Ayaline_DataflowManager_Model_SimpleXml_Element extends Varien_Simplexml_Element
{

    /**
     * @param string $xpath
     * @param bool   $shift
     * @return array|Ayaline_DataflowManager_Model_SimpleXml_Element|Ayaline_DataflowManager_Model_SimpleXml_Element[]
     */
    public function searchXpath($xpath, $shift = false)
    {
        $result = parent::xpath($xpath);
        $result = is_array($result) ? $result : array();

        if ($shift) {
            return array_shift($result);
        }

        return $result;
    }

    /**
     * @param string $xpath
     * @param null   $default
     * @return null|string
     */
    public function getValueFromXpath($xpath, $default = null)
    {
        $rawValue = $this->searchXpath($xpath, true);
        if ($rawValue !== null) {
            return trim($rawValue->__toString());
        }

        return $default;
    }

}