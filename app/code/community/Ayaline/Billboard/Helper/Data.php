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
class Ayaline_Billboard_Helper_Data extends Mage_Core_Helper_Abstract
{

    const XML_NODE_BILLBOARDS_BILLBOARD_TEMPLATE_FILTER = 'global/billboards/billboard/tempate_filter';

    /**
     * Add custom column renderer and filter
     *
     * @param Mage_Adminhtml_Block_Widget_Grid $widgetGrid
     */
    public function addBillboardTypeRendrerAndFilter($widgetGrid)
    {
        $renderers = $widgetGrid->getColumnRenderers();
        $renderers['billboard_type'] = 'ayalinebillboard/adminhtml_widget_grid_column_renderer_billboardType';
        $widgetGrid->setColumnRenderers($renderers);

        $filters = $widgetGrid->getColumnFilters();
        $filters['billboard_type'] = 'ayalinebillboard/adminhtml_widget_grid_column_filter_billboardType';
        $widgetGrid->setColumnFilters($filters);
    }

    /**
     * Retrieve billboard template processor
     *
     * @return Mage_Cms_Model_Template_Filter
     */
    public function getBillboardTemplateProcessor()
    {
        $model = (string)Mage::getConfig()->getNode(self::XML_NODE_BILLBOARDS_BILLBOARD_TEMPLATE_FILTER);

        return Mage::getModel($model);
    }

}
