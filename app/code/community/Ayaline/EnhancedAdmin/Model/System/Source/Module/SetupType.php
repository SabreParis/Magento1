<?php

/**
 * created : 2013
 *
 * @category  Ayaline
 * @package   Ayaline_EnhancedAdmin
 * @author    aYaline
 * @copyright Ayaline - 2013 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType
{

    const SETUP_TYPE_SQL = 'sql';
    const SETUP_TYPE_DATA = 'data';
    const SETUP_TYPE_BOTH = 'sql_data';

    /**
     * @param bool|false $withEmpty
     * @param bool|true  $withBoth
     * @return array
     */
    public function getOptions($withEmpty = false, $withBoth = true)
    {
        $options = array();

        if ($withEmpty) {
            $options[] = '';
        }

        if ($withBoth) {
            $options[self::SETUP_TYPE_BOTH] = Mage::helper('ayaline_enhancedadmin')->__('Sql & Data');
        }
        $options[self::SETUP_TYPE_SQL] = Mage::helper('ayaline_enhancedadmin')->__('Sql');
        $options[self::SETUP_TYPE_DATA] = Mage::helper('ayaline_enhancedadmin')->__('Data');

        return $options;
    }

    /**
     * @param bool|false $withEmpty
     * @param bool|true  $withBoth
     * @return array
     */
    public function toOptionArray($withEmpty = false, $withBoth = true)
    {
        $options = array();

        if ($withEmpty) {
            $options[] = array('value' => '', 'label' => '');
        }

        foreach ($this->getOptions(false, $withBoth) as $_value => $_label) {
            $options[] = array('value' => $_value, 'label' => $_label);
        }

        return $options;
    }

}
