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
require_once Mage::getModuleDir('controllers', 'Mage_CatalogSearch') . DS . 'ResultController.php';

class Sabre_CatalogSearch_ResultAjaxController extends Mage_CatalogSearch_ResultController
{

    /**
     * Display search result
     */
    public function indexAction()
    {
        if (!$this->getRequest()->isAjax()) {
            $this->_forward('noRoute');
            return;
        }
        parent::indexAction();
    }
}
