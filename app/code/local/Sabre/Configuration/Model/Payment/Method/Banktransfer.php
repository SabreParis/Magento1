<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 04/02/2016
 * Time: 11:28
 */
class Sabre_Configuration_Model_Payment_Method_Banktransfer extends Mage_Payment_Model_Method_Banktransfer
{

    protected $_canReviewPayment = true;
    protected $_isInitializeNeeded = true;

    public function getConfigPaymentAction() {
        return true;
    }

    public function acceptPayment(Mage_Payment_Model_Info $payment) {
        parent::acceptPayment($payment);
        if ($this->getConfigData('order_status_accept_payment') == 'pending') {
            $payment->getOrder()->setState(Mage_Sales_Model_Order::STATE_NEW);
            $payment->getOrder()->setStatus('pending');
            return false;
        }
        return true;
    }

    public function denyPayment(Mage_Payment_Model_Info $payment) {
        parent::denyPayment($payment);
        return true;
    }

    public function initialize($paymentAction, $stateObject) {
        if ($this->getConfigData('order_status') == Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW) {
            $state = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
            $stateObject->setState($state);
            $stateObject->setStatus($state);
            $stateObject->setIsNotified(false);
        }
    }

}