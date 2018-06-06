<?php
class Tft_Tftorders_Model_Tftpay extends Mage_Payment_Model_Method_Abstract
{
	 /**
     * Payment Method features
     * @var bool
     */
    protected $_canAuthorize = true;
	protected $_canUseCheckout = true;
	
	protected $_code = 'tftorders';
	
	/**
     * Check whether method is available
     *
     * @param Mage_Sales_Model_Quote|null $quote
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        return parent::isAvailable($quote) && !empty($quote)
            && Mage::app()->getStore()->roundPrice($quote->getGrandTotal()) == 0;
    }
    /**
     * Get config payment action, do nothing if status is pending
     *
     * @return string|null
     */
    public function getConfigPaymentAction()
    {
        return $this->getConfigData('order_status') == 'pending' ? null : parent::getConfigPaymentAction();
    }
	
}
?>