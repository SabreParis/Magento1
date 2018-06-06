<?php

/**
 * created : 2014
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
interface Ayaline_Core_Block_Adminhtml_Widget_Grid_Interface
{

    /**
     * Return Grid header label
     *
     * @return string
     */
    public function getGridHeader();

    /**
     * Can show grid in grids
     *
     * @return bool
     */
    public function canShowGrid();

    /**
     * Grid is hidden
     *
     * @return bool
     */
    public function isHidden();

}