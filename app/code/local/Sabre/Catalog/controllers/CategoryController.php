<?php

/**
 * Created : 2015
 * 
 * @category Sabre
 * @package Sabre_Catalog
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
require_once Mage::getModuleDir('controllers', 'Mage_Catalog') . DS . 'CategoryController.php';

class Sabre_Catalog_CategoryController extends Mage_Catalog_CategoryController {

    /**
     * Category view action
     */
    public function ajaxViewAction() {
        if (!$this->getRequest()->isAjax()) {
            $this->_forward('noRoute');
            return;
        }

        if (($category = $this->_initCatagory())) {
            $this->loadLayout(false);

            $this->renderLayout();
        } elseif (!$this->getResponse()->isRedirect()) {
            $this->_forward('noRoute');
        }
    }

}
