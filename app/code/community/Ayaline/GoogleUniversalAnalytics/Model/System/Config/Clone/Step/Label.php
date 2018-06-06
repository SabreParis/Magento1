<?php

/**
 * created: 2016
 *
 * @category  XXXXXXX
 * @package   Ayaline
 * @author    aYaline Magento <support.magento-shop@ayaline.com>
 * @copyright 2016 - aYaline Magento
 * @license   aYaline - http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 * @link      http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_GoogleUniversalAnalytics_Model_System_Config_Clone_Step_Label extends Mage_Core_Model_Config_Data
{
    public function getSteps()
    {
        /* @var Mage_Checkout_Block_Onepage $onePage */
        $onePage = Mage::getBlockSingleton('checkout/onepage');
        $steps = array_keys($onePage->getSteps());
        if (in_array('login', $steps)) {
            $key = array_search('login', $steps);
            $steps[$key] = 'register';
        }
        $steps[] = 'save_order';

        return $steps;
    }
    /**
     * Get fields prefixes
     *
     * @return array
     */
    public function getPrefixes()
    {
        $prefixes = array();

        foreach ($this->getSteps() as $step) {
            $prefixes[] = array(
                'field' => 'opc-'.$step.'_',
                'label' => ucfirst(str_replace('_', ' ', $step)).' step label',
            );
        }
        return $prefixes;
    }
}