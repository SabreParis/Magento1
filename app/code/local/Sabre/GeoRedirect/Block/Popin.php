<?php

/**
 * created :    2016
 *
 * @category    Ayaline
 * @package     Sabre_GeoRedirect
 * @author      aYaline
 * @copyright   Ayaline - 2016 - http://magento-shop.ayaline.com
 * @license     http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_GeoRedirect_Block_Popin extends Mage_Core_Block_Template
{

    const REDIRECT_FRONTNAME_CONFIG = 'sabre_georedirect';

    public function canShow()
    {
        if (!Mage::getStoreConfigFlag(Sabre_GeoRedirect_Helper_Data::CONFIG_MODULE_ENABLE)) {
            return false;
        }
        if (Mage::getStoreConfigFlag(Sabre_GeoRedirect_Helper_Data::CONFIG_FORCE_REDIRECT)) {
            // Si la redirection est forcée, inutile de présenter la popin.
            return false;
        }
        // On teste si la requête émane d'un robot
        if ($this->helper("sabre_georedirect")->isExcludedBotsRequest()) {
            return false;
        }
        // On teste si l'utilisateur a déjà un cookie
        if (!$this->helper("sabre_georedirect/cookie")->isCookieShouldRedirect()) {
            return false;
        }
        // On teste si l'utilisateur n'est pas dans un pays accepté
        if (!$this->helper("sabre_georedirect")->isFromNotAllowedCountry()) {
            return false;
        }
        return true;
    }

    public function getCurrentWebsite()
    {
        return Mage::app()->getWebsite();
    }

    public function getDestinationWebsite()
    {
        return $this->helper('sabre_georedirect')->getDestinationWebsite();
    }

    public function getFormActionUrl()
    {
        $frontName =
            (string)Mage::getConfig()->getNode(
                'frontend/routers/' . self::REDIRECT_FRONTNAME_CONFIG . '/args/frontName'
            );
        return Mage::getUrl($frontName);
    }

    public function getVisitorCountryName()
    {
        return Mage::app()->getLocale()->getCountryTranslation($this->getVisitorCountry());
    }

    public function getVisitorCountry()
    {
        $country = $this->helper('sabre_georedirect')->getVisitorCountry();
        return $country;
    }
}
