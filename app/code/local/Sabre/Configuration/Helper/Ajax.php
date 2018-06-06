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
class Sabre_Configuration_Helper_Ajax extends Mage_Core_Helper_Abstract
{

    /**
     * @param Mage_Core_Model_Session_Abstract $session
     * @return string
     */
    public function getSessionMessageHtml($session)
    {
        /** @var $messages Mage_Core_Model_Message_Collection */
        $messages = $session->getMessages(true); // get messages and flush
        /** @var $messageBlock Mage_Core_Block_Messages */
        $messageBlock = Mage::getSingleton('core/layout')->createBlock('core/messages', 'ajax_messages');
        $messageBlock->setMessages($messages);

        return $messageBlock->getGroupedHtml();
    }

    /**
     * @param Mage_Core_Controller_Varien_Action $controller
     * @param string|array|Varien_Object         $responseToEncode
     * @param int                                $responseCode
     */
    public function sendJsonResponse($controller, $responseToEncode, $responseCode = 200)
    {
        //  Force no cache for IE...
        $controller->getResponse()->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate', true);
        $controller->getResponse()->setHeader('Cache-Control', 'post-check=0, pre-check=0');
        $controller->getResponse()->setHeader('Pragma', 'no-cache', true);

        $controller->getResponse()->setHttpResponseCode($responseCode);
        $controller->getResponse()->setHeader('Content-type', 'application/json');
        $controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($responseToEncode));
    }

}
