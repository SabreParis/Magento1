<?php

/**
 * created : 2017
 *
 * @category Ayaline
 * @package Sabre_GeoRedirect
 * @author aYaline
 * @copyright Ayaline - 2017 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_GeoRedirect_Model_Observer
{
    /* @var Sabre_GeoRedirect_Helper_Data */
    protected $_helper;

    function __construct()
    {
        $this->_helper = Mage::helper('sabre_georedirect');
    }

    public function forceRedirection(Varien_Event_Observer $observer) {

        $event = $observer->getEvent();
        /* @var $action Mage_Core_Controller_Varien_Action */
        $action = $event->getData("controller_action");

        if (!Mage::getStoreConfigFlag(Sabre_GeoRedirect_Helper_Data::CONFIG_MODULE_ENABLE)) {
            // Si le module n'est pas activé... on sort
            return $this;
        }
        if (!Mage::getStoreConfigFlag(Sabre_GeoRedirect_Helper_Data::CONFIG_FORCE_REDIRECT)) {
            // Si la redirection n'est pas forcée... on sort
            return $this;
        }
        if ($this->_helper->isRedirectionBlockedByIp()) {
            // L'IP est autorisée à ne pas tenir compte de la redirection
            return $this;
        }
        if ($action->getRequest()->isAjax()) {
            // On teste si ce n'est pas en ajax... pas la peine de s'embêter à faire deux fois le test.
            return $this;
        }
        // On teste si la requête émane d'un robot
        if ($this->_helper->isExcludedBotsRequest()) {
            return $this;
        }

        $visitorCountry = $this->_helper->getVisitorCountry();
        $this->_helper->log("Le visiteur souhaite accéder au website " . Mage::app()->getWebsite()->getCode());
        $this->_helper->log("\t Le visiteur vient du pays : $visitorCountry");


        $isFromNotAllowedCountry = $this->_helper->isFromNotAllowedCountry();
        if (!$isFromNotAllowedCountry) {
            $this->_helper->log("\t Le visiteur vient d'un pays autorisé");
            return $this;
        }

        $this->_helper->log("Le visiteur vient d'un pays non autorisé : on recherche le site web de son pays");
        /* @var $destinationWebsite Mage_Core_Model_Website */
        $destinationWebsite = $this->_helper->getDestinationWebsite();
        if (!$destinationWebsite || !$destinationWebsite->getId()) {
            $this->_helper->log("\t Aucun pays vers lequel rediriger n'a été trouvé - on redirige vers le website par défaut");

            $destinationWebsite = $this->_helper->getDefaultDestinationWebsite();
            if (!$destinationWebsite || !$destinationWebsite->getId()) {
                $this->_helper->log("\t Aucun website par défaut - on reste sur le site.");
                return $this;
            } else {
                // On vérifie que l'on est pas déjà sur le site par défaut...
                if (Mage::app()->getWebsite()->getId()==$destinationWebsite->getId()) {
                    $this->_helper->log("\t Nous sommes déjà sur le site par défaut. On ne bouge donc pas");
                    return $this;
                }
            }
        }

        // Rediriger vers le website trouvé
        $url = $destinationWebsite->getDefaultStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $this->_helper->log("\t Redirection vers le website approprié.");
        $action->getResponse()->setRedirect($url);

    }

}