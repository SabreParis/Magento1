<?php

/**
 * created: 2015
 *
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Adminhtml_Block_Catalog_Category_Helper_Model extends Varien_Data_Form_Element_Select
{
    public function getValues()
    {
        $return = [
            ['value' => '', 'label' => ''],
        ];

        $attributesSet = array('cutlery', 'porcelain', 'accessory');
        foreach ($attributesSet as $attSet) {
            $attribute = Mage::getSingleton('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_model_' . $attSet);
            $options = $attribute->getSource()->getAllOptions(false);
            $options[] = array(
                'value' => '',
                'label' => 'Tous'
            );
            $return[$attSet]['label'] = $attSet;
            foreach ($options as $o) {
                $opt[$o['value']] = $o['label'];
                $return[$attSet]['value'][] =
                    array(
                        'value' => $o['value'],
                        'label' => $o['label']
                    );
            }
        }

        return $return;

    }


}
