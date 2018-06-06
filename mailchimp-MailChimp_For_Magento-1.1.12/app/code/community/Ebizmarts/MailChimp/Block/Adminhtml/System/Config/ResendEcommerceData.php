<?php
/**
 * mc-magento Magento Component
 *
 * @category  Ebizmarts
 * @package   mc-magento
 * @author    Ebizmarts Team <info@ebizmarts.com>
 * @copyright Ebizmarts (http://ebizmarts.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @date:     5/27/16 1:16 PM
 * @file:     ResendEcommerceData.php
 */
class Ebizmarts_MailChimp_Block_Adminhtml_System_Config_ResendEcommerceData
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('ebizmarts/mailchimp/system/config/resendecommercedata.phtml');
    }

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }

    public function getButtonHtml()
    {
        $helper = $this->makeHelper();
        $scopeArray = $helper->getCurrentScope();
        if ($helper->getIfConfigExistsForScope(Ebizmarts_MailChimp_Model_Config::GENERAL_MCSTOREID, $scopeArray['scope_id'], $scopeArray['scope'])) {
            $label = $helper->__('Resend Ecommerce Data');
            $button = $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'id' => 'resendecommercedata_button',
                        'label' => $label,
                        'onclick' => 'javascript:resendecommerce(); return false;',
                        'title' => $helper->__('Resend Ecommerce Data current scope')
                    )
                );

            return $button->toHtml();
        }
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        $helper = $this->makeHelper();
        $message = 'Are you sure you want to delete the local data in order to send all items again?\nAutomations will work normally but the synchronization process for the old data will take longer than resetting the MailChimp store.';
        return $helper->__($message);
    }

    public function getAjaxCheckUrl()
    {
        $helper = $this->makeHelper();
        $scopeArray = $helper->getCurrentScope();
        return Mage::helper('adminhtml')->getUrl('adminhtml/ecommerce/resendEcommerceData', array('scope' => $scopeArray['scope'], 'scope_id' => $scopeArray['scope_id']));
    }

    /**
     * @return Ebizmarts_MailChimp_Helper_Data
     */
    protected function makeHelper()
    {
        return $this->helper('mailchimp');
    }

}
