<?php

/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Billboard_Controller_Billboard extends Mage_Core_Controller_Varien_Router_Abstract
{

    /**
     * Initialize Controller Router
     *
     * @param Varien_Event_Observer $observer
     */
    public function initControllerRouters($observer)
    {
        /* @var $front Mage_Core_Controller_Varien_Front */
        $front = $observer->getEvent()->getFront();

        $front->addRouter('ayaline_billboard', $this);
    }

    /**
     * {@inheritdoc}
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
        $identifier = trim($request->getPathInfo(), '/');

        $billboard = Mage::getResourceSingleton('sabrebillboard/billboard');
        $billboardId = $billboard->checkLandingPageIdentifier($identifier, Mage::app()->getStore()->getId());
        if (!$billboardId) {
            return false;
        }

        $request->setModuleName('billboard')
                ->setControllerName('index')
                ->setActionName('index')
                ->setParam('id', $billboardId);
        $request->setAlias(
            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
            $identifier
        );

        return true;
    }
}
