<?php

/**
 * created: 2015
 *
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Adminhtml_Block_Catalog_Category_Helper_Color extends Varien_Data_Form_Element_Select
{
    public function getValues()
    {

        $return = [
            ['value' => '', 'label' => ''],
        ];

        $attribute = Mage::getSingleton('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_filter_color');
        $options = $attribute->getSource()->getAllOptions(false);
        $options[] = array(
            'value' => '',
            'label' => 'Tous'
        );
        foreach ($options as $o) {
            $opt[$o['value']] = $o['label'];
            $return[] = ['value' => $o['value'], 'label' => $o['label']];
        }

        return $return;
    }
}
