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
class Ayaline_Billboard_Block_Adminhtml_Widget_Grid_Column_Filter_BillboardType extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Abstract
{

    public function getHtml()
    {
        //$value = $this->getColumn()->getValue();

        /* @var $collection Ayaline_Billboard_Model_Mysql4_Billboard_Type_Collection */
        $collection = Mage::getResourceModel('ayalinebillboard/billboard_type_collection');

        $html = '<select name="' . $this->_getHtmlName() . '" ' . $this->getColumn()->getValidateClass() . '>';
        $value = $this->getValue();

        $html .= '<option value=""' . (!$value ? ' selected="selected"' : '') . '></option>';

        foreach ($collection as $_billboardType) {
            $html .= '<option value="' . $_billboardType->getId() . '"' . ($value == $_billboardType->getId() ? ' selected="selected"' : '') . '>';
            $html .= $_billboardType->getTitle();
            $html .= '</option>';
        }

        $html .= '</select>';

        return $html;
    }

    public function getCondition()
    {
        if (is_null($this->getValue())) {
            return null;
        }

        return array('eq' => $this->getValue());
    }

}