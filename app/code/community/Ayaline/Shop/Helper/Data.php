<?php

/**
 * created : 10/03/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Shop
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_Shop_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getCountriesOption()
    {
        $options = Mage::getResourceModel('directory/country_collection')->loadData()->toOptionArray(false);
        array_unshift($options, array('value' => '', 'label' => Mage::helper('adminhtml')->__('--Please Select--')));

        $list = array();
        foreach ($options as $option) {
            $list[$option['value']] = $option['label'];
        }

        return $list;
    }
}
