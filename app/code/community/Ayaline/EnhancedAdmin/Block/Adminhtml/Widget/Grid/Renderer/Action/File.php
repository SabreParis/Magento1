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
class Ayaline_EnhancedAdmin_Block_Adminhtml_Widget_Grid_Renderer_Action_File extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{

    /**
     * Render single action as link html
     *
     * @param array         $action
     * @param Varien_Object $row
     * @return string
     */
    protected function _toLinkHtml($action, Varien_Object $row)
    {
        $actionAttributes = new Varien_Object();

        if (isset($action['url']) && is_array($action['url'])) {
            if (isset($action['url']['row_params']) && is_array($action['url']['row_params'])) {
                foreach ($action['url']['row_params'] as $_code => $_getter) {
                    $action['url']['params'][$_code] = $row->$_getter();
                }
            }
        }

        $actionCaption = '';
        $this->_transformActionData($action, $actionCaption, $row);

        $confirm = false;
        if (isset($action['confirm'])) {
            $confirm = true;
            unset($action['confirm']);
        }
        $action['onclick'] = "return varienGridAction.run(this, {$confirm});";

        $actionAttributes->setData($action);

        return '<a ' . $actionAttributes->serialize() . '>' . $actionCaption . '</a>';
    }

} 