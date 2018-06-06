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
class Ayaline_EnhancedAdmin_Block_Adminhtml_Widget_Grid_Renderer_Text_Versions extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    /**
     * @param Varien_Object|Ayaline_EnhancedAdmin_Model_Module $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $html = '';
        $size = $row->getAvailableSetups()->getSize();
        $index = 0;
        foreach ($row->getAvailableSetups() as $_setup) {
            $html .= "{$_setup->getCode()}<br />";
            $html .= str_repeat('&nbsp;', 3) . "sql: {$_setup->getDbVersion()}<br />";
            $html .= str_repeat('&nbsp;', 3) . "data: {$_setup->getDataVersion()}<br />";

            $index++;
            if ($size > $index) {
                $html .= '<hr />';
            }
        }

        return $html;
    }

} 