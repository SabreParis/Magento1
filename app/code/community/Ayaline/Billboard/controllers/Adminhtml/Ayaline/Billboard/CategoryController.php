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
require_once 'Mage' . DS . 'Adminhtml' . DS . 'controllers' . DS . 'Catalog' . DS . 'CategoryController.php';

class Ayaline_Billboard_Adminhtml_Ayaline_Billboard_CategoryController extends Mage_Adminhtml_Catalog_CategoryController
{

    public function billboardAction()
    {
        if (!$this->_initCategory()) {
            return;
        }
        $this->loadLayout()->renderLayout();
    }

    public function billboardGridAction()
    {
        if (!$this->_initCategory()) {
            return;
        }
        $this->loadLayout()->renderLayout();
    }

}