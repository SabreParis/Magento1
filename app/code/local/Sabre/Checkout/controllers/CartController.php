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

require_once Mage::getModuleDir('controllers', 'Owebia_Shipping2') . DS . 'Checkout' . DS . 'CartController.php';

class Sabre_Checkout_CartController extends Owebia_Shipping2_Checkout_CartController
{

    /**
     * Get checkout express session model instance
     *
     * @return Sabre_Checkout_Model_Express_Session
     */
    protected function _getExpressSession()
    {
        return Mage::getSingleton('sabre_checkout/express_session');
    }

    public function preDispatch()
    {
        parent::preDispatch();

        // ensure ajax call
        if (!$this->getRequest()->isAjax() && strpos($this->getRequest()->getActionName(), 'express') !== false) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            $this->_redirect('');

            return $this;
        }

        return $this;
    }

    /**
     * Duplicate from parent to avoid success message
     *  and add flag to show mini cart
     */
    public function addAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_goBack();

            return;
        }

        $cart = $this->_getCart();
        $params = $this->getRequest()->getParams();
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_initProduct();
            $related = $this->getRequest()->getParam('related_product');

            /**
             * Check product availability
             */
            if (!$product) {
                $this->_goBack();

                return;
            }

            $cart->addProduct($product, $params);
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);

            /**
             * @todo remove wishlist observer processAddToCart
             */
            Mage::dispatchEvent(
                'checkout_cart_add_product_complete',
                array(
                    'product'  => $product,
                    'request'  => $this->getRequest(),
                    'response' => $this->getResponse(),
                )
            );

            if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()) {
                    $this->_getSession()->setData('show_mini_cart', true);
                }
                $this->_goBack();
            }
        } catch (Mage_Core_Exception $e) {
            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError(Mage::helper('core')->escapeHtml($message));
                }
            }

            $url = $this->_getSession()->getRedirectUrl(true);
            if ($url) {
                $this->getResponse()->setRedirect($url);
            } else {
                $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            }
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
            Mage::logException($e);
            $this->_goBack();
        }
    }

    ###########################
    ## Express Order Actions ##
    ###########################

    public function expressTableAction()
    {
        if (Mage::helper('catalog/product')->initProduct($this->getRequest()->getParam('product_id'), $this)) {
            $this->loadLayout(false)->renderLayout();
        } else {
            $this->getResponse()->setBody('');
        }
    }

    public function expressAddAction()
    {
        $response = [
            'success'           => false,
            'messages'          => '',
            'html'              => '',
            'cart_header_count' => 0,
            'cart_header_html'  => '',
        ];

        if ($this->_validateFormKey()
            && Mage::helper('catalog/product')->initProduct($this->getRequest()->getParam('product_id'), $this)
        ) {
            $cart = $this->_getCart();
            $requestedProducts = $this->getRequest()->getParam('product', []);
            try {
                if (!count($requestedProducts)) {
                    Mage::throwException($this->__('Please select product(s).'));
                }

                $filter = new Zend_Filter_LocalizedToNormalized(['locale' => Mage::app()->getLocale()->getLocaleCode()]);

                $nbProductProcessed = 0;
                foreach ($requestedProducts as $_productId => $_itemData) {
                    $_newQty = $filter->filter($_itemData['new_qty']);
                    $_oldQty = $filter->filter($_itemData['old_qty']);

                    // dispatch case
                    //  -> no change
                    if ($_newQty == $_oldQty) {
                        continue;
                    }

                    if ($_newQty) {
                        //  -> add / update
                        try {
                            if ($_itemData['item_id']) {
                                $cart->updateItem($_itemData['item_id'], $_newQty);
                            } else {
                                $cart->addProduct($_productId, $_newQty);
                            }

                            $nbProductProcessed++;
                        } catch (Mage_Core_Exception $e) {
                            $this->_getExpressSession()->addError($e->getMessage());
                        } catch (Exception $e) {
                            $_productName = Mage::getResourceSingleton('catalog/product')->getAttributeRawValue($_productId, 'name', Mage::app()->getStore());
                            $_productName = Mage::helper('core')->escapeHtml($_productName);
                            $this->_getExpressSession()->addException($e, $this->__('An error occurred while processing "%s".', $_productName));
                        }
                    } else {
                        //  -> delete
                        if ($_itemData['item_id']) {
                            $cart->removeItem($_itemData['item_id']);
                            $nbProductProcessed++;
                        }
                    }
                }

                if ($nbProductProcessed === 0) {
                    Mage::throwException($this->__('Please select product(s).'));
                }

                $cart->save();

                $this->_getSession()->setCartWasUpdated(true);

                if (!$cart->getQuote()->getHasError()) {
                    $this->_getExpressSession()->addSuccess($this->__('Your shopping cart has been updated.'));
                    $response['success'] = true;
                    $response['cart_header_count'] = $cart->getSummaryQty();
                    $response['cart_header_html'] = $this->getHeaderMiniCartHtml();
                } else {
                    $this->_getExpressSession()->addNotice($this->__('Something get wrong, please try again later.'));
                }

            } catch (Mage_Core_Exception $e) {
                if ($this->_getSession()->getUseNotice(true)) {
                    $this->_getExpressSession()->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
                } else {
                    $messages = array_unique(explode("\n", $e->getMessage()));
                    foreach ($messages as $message) {
                        $this->_getExpressSession()->addError(Mage::helper('core')->escapeHtml($message));
                    }
                }
            } catch (Exception $e) {
                $this->_getExpressSession()->addException($e, $this->__('Cannot update your shopping cart.'));
                Mage::logException($e);
            }
        } else {
            $this->_getExpressSession()->addError($this->__('An error occurred. Please refresh this page and re-try.'));
        }


        if ($response['success']) {
            $this->loadLayout('checkout_cart_expresstable')
                 ->_initLayoutMessages('sabre_checkout/express_session')
                 ->renderLayout();
            $response['html'] = $this->getResponse()->getBody();
        } else {
            $response['messages'] = Mage::helper('sabre_configuration/ajax')->getSessionMessageHtml($this->_getExpressSession());
        }

        Mage::helper('sabre_configuration/ajax')->sendJsonResponse($this, $response);
    }

    protected function getHeaderMiniCartHtml()
    {
        /** @var Mage_Core_Model_Layout $newLayout */
        $newLayout = Mage::getModel('core/layout');

        $newLayout->getUpdate()->load('checkout_cart_expresstable_header_minicart');
        $newLayout->generateXml();
        $newLayout->generateBlocks();

        return $newLayout->getOutput();
    }

}
