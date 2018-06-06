<?php

/**
 * created : 2014
 *
 * @category  Ayaline
 * @package   Ayaline_EnhancedAdmin
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupCode
{

    /**
     * @param Ayaline_EnhancedAdmin_Model_Module $module
     * @param bool                               $withEmpty
     * @return array
     */
    public function getOptions($module, $withEmpty = false)
    {
        $options = array();
        if ($withEmpty) {
            $options[] = '';
        }

        foreach ($module->getAvailableSetups() as $_setupCode => $_setupVersion) {
            $options[$_setupCode] = $_setupCode;
        }

        return $options;
    }

    /**
     * @param Ayaline_EnhancedAdmin_Model_Module $module
     * @param bool                               $withEmpty
     * @return array
     */
    public function toOptionArray($module, $withEmpty = false)
    {
        $options = array();

        if ($withEmpty) {
            $options[] = array('value' => '', 'label' => '');
        }

        foreach ($this->getOptions($module, false) as $_value => $_label) {
            $options[] = array('value' => $_value, 'label' => $_label);
        }

        return $options;
    }

}
