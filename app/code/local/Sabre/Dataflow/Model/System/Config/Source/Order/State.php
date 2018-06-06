<?php
/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Sabre_Dataflow
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 * Order Statuses source model
 */
class Sabre_Dataflow_Model_System_Config_Source_Order_State
{

    // set null to enable all possible
    protected $_states = array(
        /**
         * Order states
         */
        Mage_Sales_Model_Order::STATE_NEW,
        Mage_Sales_Model_Order::STATE_PENDING_PAYMENT,
        Mage_Sales_Model_Order::STATE_PROCESSING,
        Mage_Sales_Model_Order::STATE_COMPLETE,
        Mage_Sales_Model_Order::STATE_CLOSED,
        Mage_Sales_Model_Order::STATE_CANCELED,
        Mage_Sales_Model_Order::STATE_HOLDED,
        Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW,
    );

    public function toOptionArray()
    {
        $options = array();
//        $options[] = array(
//            'value' => '',
//            'label' => Mage::helper('adminhtml')->__('-- Please Select --')
//        );
        foreach ($this->_states as $code) {
            $options[] = array(
                'value' => $code,
                'label' => Mage::getSingleton('sales/order_config')->getStateLabel($code)
            );
        }
        return $options;
    }
}
