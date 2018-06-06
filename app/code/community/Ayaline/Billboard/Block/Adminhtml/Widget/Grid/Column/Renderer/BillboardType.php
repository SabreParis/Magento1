<?php
/**
 * created : 10/08/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 *
 * @package Ayaline_Billboard
 */
class Ayaline_Billboard_Block_Adminhtml_Widget_Grid_Column_Renderer_BillboardType extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    /**
     * Render row answer views
     *
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        /* @var $row Ayaline_Billboard_Model_Billboard */
        $out = '';
        foreach ($row->getTypes() as $_type) {
            $out .= $_type->getTitle() . '<br />';
        }

        return $out;
    }

    /**
     * Render row answer views for export
     *
     * @param Varien_Object $row
     * @return string
     */
    public function renderExport(Varien_Object $row)
    {
        /* @var $row Ayaline_Billboard_Model_Billboard */
        $out = array();
        foreach ($row->getTypes() as $_type) {
            $out[] = $_type->getTitle();
        }

        return implode(';', $out);
    }

}