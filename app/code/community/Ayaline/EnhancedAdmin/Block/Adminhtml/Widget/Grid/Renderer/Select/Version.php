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
class Ayaline_EnhancedAdmin_Block_Adminhtml_Widget_Grid_Renderer_Select_Version extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    /**
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $html = '';
        $options = Mage::getSingleton('ayaline_enhancedadmin/system_source_module_version')->getOptions($row, $this->getColumn()->getMode());
        if (count($options)) {
            $name = $this->getColumn()->getName() ? $this->getColumn()->getName() : $this->getColumn()->getId();
            $html .= '<select name="' . $this->escapeHtml($name) . '" ' . $this->getColumn()->getValidateClass() . ' style="width:250px;">';
            $html .= '<option value=""></option>';

            $value = $row->getData($this->getColumn()->getIndex());
            foreach ($options as $val => $label) {
                $selected = (($val == $value && (!is_null($value))) ? ' selected="selected"' : '');
                $html .= '<option value="' . $this->escapeHtml($val) . '"' . $selected . '>';
                $html .= $this->escapeHtml($label);
                $html .= '</option>';
            }
            $html .= '</select>';
        } else {
            $html .= '-';
        }

        return $html;
    }

}