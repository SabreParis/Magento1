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

require_once Mage::getModuleDir('controllers', 'Mage_Checkout') . DS . 'OnepageController.php';

class Sabre_Checkout_OnepageController extends Mage_Checkout_OnepageController
{

    /**
     * Save checkout billing address
     */
    public function saveBillingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }
            $result = $this->getOnepage()->saveBilling($data, $customerAddressId);

            if (!isset($result['error'])) {
                if ($this->getOnepage()->getQuote()->isVirtual()) {
                    $result['goto_section'] = 'payment';
                    $result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                } elseif (isset($data['use_for_shipping'])) {
                    $result['goto_section'] = 'shipping';
                    if ($data['use_for_shipping'] == 0 || $data['use_for_shipping'] == 1) {
                        Mage::register('Shipping_ups', true);
                    }
                    if ($data['use_for_shipping'] == 1 || $data['use_for_shipping'] == 2) {
                        if ($data['use_for_shipping'] == 2) {
                            Mage::register('Withdraw_rate', true);
                            //Save bills address as default shipping address.
                            $this->getOnepage()->saveShipping($data, $customerAddressId);
                        }
                        $result['goto_section'] = 'shipping_method';
                        $result['update_section'] = array(
                            'name' => 'shipping-method',
                            'html' => $this->_getShippingMethodsHtml()
                        );

                        $result['allow_sections'] = array('shipping');
                        $result['duplicateBillingInfo'] = 'true';
                    }
                }
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    public function saveShippingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping', array());
            Mage::register('Shipping_ups', true);
            if (isset($data['same_as_billing'])) {
                if ($data['same_as_billing'] == 2) {
                    Mage::register('Withdraw_rate', true);
                }
            }
        }
        Mage::getSingleton('checkout/session')->unsetData('backup_shipping_address');
        parent::saveShippingAction();
    }

    public function saveShippingMethodAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            //Si Shipping method != Point Relais UPS, on supprime la post-data que on n'a pas besoin.
            if ($dataPost['shipping_method'] !== ' owebiashipping4_FREE') {
                foreach ($dataPost as $key => $value) {
                    if (!($key === 'shop' || $key === 'shipping_method')) {
                        unset($dataPost[$key]);
                    }
                }
            }
            $data = $this->getRequest()->getPost('shipping_method', '');
            $result = $this->getOnepage()->saveShippingMethod($data);
            // $result will contain error data if shipping method is empty
            if (!$result) {
                Mage::dispatchEvent(
                    'checkout_controller_onepage_save_shipping_method',
                    array(
                        'request' => $this->getRequest(),
                        'quote' => $this->getOnepage()->getQuote()
                    ));
                $this->getOnepage()->getQuote()->collectTotals();
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

                $result['goto_section'] = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );
            }
            $this->getOnepage()->getQuote()->collectTotals()->save();
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    protected function _getPaymentMethodsHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_paymentmethod');
        $this->initLayoutMessages('checkout/session');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    public function updateOpTotalAction()
    {
        if (Mage::getSingleton('checkout/session')->getQuote()) {
            $total = Mage::getSingleton('checkout/session')->getQuote()->getStore()->formatPrice(Mage::getSingleton('checkout/session')->getQuote()->getGrandTotal(), false);
            $this->getResponse()->setHeader('Content-type', 'application/json', true);
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($total));
        }
    }
}