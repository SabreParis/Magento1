<?php

/**
 * created :    2016
 *
 * @category    Sabre
 * @package     Sabre_GeoRedirect
 * @author      aYaline
 * @copyright   Ayaline - 2016 - http://magento-shop.ayaline.com
 * @license     http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_GeoRedirect_Helper_Data extends Mage_Core_Helper_Abstract
{

    const XML_PATH_EXCLUDED_BOTS = 'sabre_georedirect/general/excludedbots';

    const ACTION_STAY = "stay";
    const ACTION_LEAVE = "leave";


    const WORDING_WEBSITE_NAME = "sabre_georedirect/wordings/website_name";
    const WORDING_TITLE = "sabre_georedirect/wordings/title";
    const WORDING_TEXT = "sabre_georedirect/wordings/text";
    const WORDING_STAY = "sabre_georedirect/wordings/stay";
    const WORDING_LEAVE = "sabre_georedirect/wordings/leave";

    const CONFIG_MODULE_ENABLE = "sabre_georedirect/general/enabled";
    const CONFIG_FORCE_REDIRECT = "sabre_georedirect/general/force_redirection";
    const CONFIG_LOG_REDIRECTIONS = "sabre_georedirect/general/log_redirections";
    const CONFIG_ALLOWED_COUNTRIES = "sabre_georedirect/general/allowedcountries";
    const CONFIG_DEFAULT_WEBSITE = "sabre_georedirect/general/default_redirection_website";
    const CONFIG_IPS_ALLOWED = "sabre_georedirect/general/allow_ips";
    const REDIRECT_FRONTNAME_CONFIG = 'sabre_georedirect';

    /**
     * @param $code String
     * @param $currentWebsite Mage_Core_Model_Website
     * @param $destinationWebsite Mage_Core_Model_Website
     * @return String
     */
    public function getWording($code, $currentWebsite, $destinationWebsite)
    {
        $wording = Mage::getStoreConfig($code);
        $filter = Mage::getModel('cms/template_filter');

        $destinationWebsiteName = Mage::getStoreConfig(self::WORDING_WEBSITE_NAME, $destinationWebsite->getDefaultStore()->getId());
        $destinationWebsiteName = $destinationWebsiteName ? $destinationWebsiteName : $destinationWebsite->getName();

        $currentWebsiteName = Mage::getStoreConfig(self::WORDING_WEBSITE_NAME, $currentWebsite->getDefaultStore()->getId());
        $currentWebsiteName = $currentWebsiteName ? $currentWebsiteName : $currentWebsite->getName();

        $variables = array();
        $variables['dest'] = $destinationWebsiteName;
        $variables['current'] = $currentWebsiteName;
        $filter->setVariables($variables);
        $wording = $filter->filter($wording);

        return $wording;
    }

    public function isFromNotAllowedCountry()
    {
        $allowedCountries = Mage::getStoreConfig(self::CONFIG_ALLOWED_COUNTRIES);
        $allowedCountries = explode(",", $allowedCountries);
        $customerCountry = $this->getVisitorCountry();
        if (in_array($customerCountry, $allowedCountries)) {
            return false;
        }
        return true;
    }

    public function getVisitorCountry()
    {
        $geoIP = Mage::getSingleton('geoip/country');

        /**
         * Returns you the ISO 3166-1 code of visitors country.
         */
        return $geoIP->getCountry();

    }

    public function isExcludedBotsRequest()
    {
        $userAgent = (string)Mage::app()->getRequest()->getHeader('User-Agent');
        $configSerialized = Mage::getStoreConfig(self::XML_PATH_EXCLUDED_BOTS);
        if ($configSerialized) {
            $excludedBots = unserialize($configSerialized);
            if (is_array($excludedBots)) {
                foreach ($excludedBots as $bot) {
                    if (preg_match("/{$bot['user_agent']}/i", $userAgent)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function getDestinationWebsite()
    {
        $visitorCountry = $this->getVisitorCountry();
        $websites = Mage::getModel('core/website')->getCollection();
        foreach ($websites as $website) {
            /* @var $website Mage_Core_Model_Website */
            $countries = Mage::getStoreConfig(self::CONFIG_ALLOWED_COUNTRIES, $website->getDefaultStore()->getId());
            if (in_array($visitorCountry, explode(',', $countries))) {
                return $website;
            }
        }
        return false;
    }

    public function getDefaultDestinationWebsite() {
        $defaultWebsiteID = Mage::getStoreConfig(self::CONFIG_DEFAULT_WEBSITE);
        if ($defaultWebsiteID) {
            $defaultWebsite = Mage::getModel('core/website')->load($defaultWebsiteID);
            if ($defaultWebsite && $defaultWebsite->getId()) {
                return $defaultWebsite;
            }
        }
        return false;
    }

    public function log($message) {
        $currentWebsite = Mage::app()->getWebsite();
        $canlog = Mage::getStoreConfigFlag(self::CONFIG_LOG_REDIRECTIONS);
        if ($canlog) {
            if (is_string($message)) {
                $message = $currentWebsite->getCode() . " : " . $message;
            }
            Mage::log($message, null, "redirections.log", true);
        }
    }

    public function isRedirectionBlockedByIp()
    {
        $blocked = false;
        $allowedIps = Mage::getStoreConfig(self::CONFIG_IPS_ALLOWED);
        $remoteAddr = Mage::helper('core/http')->getRemoteAddr();
        if (!empty($allowedIps) && !empty($remoteAddr)) {
            $allowedIps = preg_split('#\s*,\s*#', $allowedIps, null, PREG_SPLIT_NO_EMPTY);
            if (in_array($remoteAddr, $allowedIps)) {
                $blocked = true;
            }
        }
        return $blocked;
    }

}
