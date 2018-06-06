<?php

/**
 * created :    2016
 *
 * @category    Ayaline
 * @package     PHS_GeoRedirect
 * @author      aYaline
 * @copyright   Ayaline - 2016 - http://magento-shop.ayaline.com
 * @license     http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_GeoRedirect_IndexController extends Mage_Core_Controller_Front_Action
{

    /**
     * @return Sabre_GeoRedirect_Helper_Cookie
     */
    protected function cookieHelper()
    {
        return Mage::helper('sabre_georedirect/cookie');
    }

    public function indexAction()
    {

        $redirectChoice     = (string) $this->getRequest()->getParam('redirect_choice', false);
        $redirectWebsite    = (string) $this->getRequest()->getParam('redirect_website', false);

        if ($redirectChoice == Sabre_GeoRedirect_Helper_Data::ACTION_LEAVE) {

            /* @var $website Mage_Core_Model_Website */
            $website = Mage::getModel('core/website')->load($redirectWebsite, 'code');
            if ($website && $website->getId()) {
                $url = $website->getDefaultStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
                $this->_redirectUrl($url);
            }
            return;

        } elseif ($redirectChoice == Sabre_GeoRedirect_Helper_Data::ACTION_STAY) {

            // Mise à jour du cookie pour ne plus proposer de redirection
            $this->cookieHelper()->registerCookie();

        }

        $this->_redirectReferer();

    }
}