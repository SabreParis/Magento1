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
class Sabre_GeoRedirect_Helper_Cookie extends Mage_Core_Helper_Abstract
{

    const COOKIE_NAME                  = 'sabre_georedirect_info';
    const COOKIE_DATA_STAY             = 'stay';

    public function registerCookie()
    {
        $cookieData = self::COOKIE_DATA_STAY;
        Mage::app()->getCookie()->set($this->getCookieName(), $cookieData, $this->getCookieLifetime(), '/');
        return $this;
    }

    public function getCookieName()
    {
        return self::COOKIE_NAME;
    }

    public function getCookieLifetime()
    {
        return 3600 * 24 * 90;// 3 months
    }

    public function isCookieShouldRedirect()
    {
        $cookieValue = Mage::app()->getCookie()->get($this->getCookieName());
        return $cookieValue==self::COOKIE_DATA_STAY ? false : true;
    }

}
