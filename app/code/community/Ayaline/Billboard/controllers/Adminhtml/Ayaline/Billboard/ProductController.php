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
require_once 'Mage' . DS . 'Adminhtml' . DS . 'controllers' . DS . 'Catalog' . DS . 'ProductController.php';

class Ayaline_Billboard_Adminhtml_Ayaline_Billboard_ProductController extends Mage_Adminhtml_Catalog_ProductController
{

    public function billboardAction()
    {
        if (!$this->_initProduct()) {
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function billboardGridAction()
    {
        if (!$this->_initProduct()) {
            return;
        }
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->getBlock('ayaline.billboard.billboard.product.grid')->toHtml());
    }

}