<?php
/**
 * created : 02/10/2015
 *
 * @category Ayaline
 * @package Ayaline_Billboard
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Billboard_IndexController extends Mage_Core_Controller_Front_Action
{

    protected function _initBillboard()
    {
        $id = $this->getRequest()->getParam('id');

        $lp = Mage::getModel('ayalinebillboard/billboard')->load($id);

        if (!$lp->getId()) {
            Mage::throwException("Unknown billboard");
        }

        if (!$lp->isLandingPage()) {
            Mage::throwException("Billboard is not a landing page");
        }

        Mage::register('current_landing_page', $lp);

        return $lp;
    }

    public function indexAction()
    {
        try {
            $this->_initBillboard();

            $this->loadLayout()->renderLayout();
        } catch (Mage_Core_Exception $e) {
            $this->_forward('noRoute');
        } catch (Exception $e) {
            $this->_forward('noRoute');
        }

    }

}