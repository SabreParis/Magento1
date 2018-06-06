<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 04/02/2016
 * Time: 12:14
 */
class Sabre_Configuration_Model_Adminhtml_System_Config_Source_Order_Status extends Mage_Adminhtml_Model_System_Config_Source_Order_Status
{

    // set null to enable all possible
    protected $_stateStatuses = array(
        Mage_Sales_Model_Order::STATE_NEW,
        Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW,
        Mage_Sales_Model_Order::STATE_PROCESSING,
    );

    public function toOptionArray()
    {
        if ($this->_stateStatuses) {
            if (is_array($this->_stateStatuses)) {
                $statuses = array();
                foreach ($this->_stateStatuses as $_state) {
                    $s = Mage::getSingleton('sales/order_config')->getStateStatuses($_state);
                    foreach ($s as $code => $label) {
                        $statuses[$code] = $label;
                    }
                }
            } else {
                $statuses = Mage::getSingleton('sales/order_config')->getStateStatuses($this->_stateStatuses);
            }
        } else {
            $statuses = Mage::getSingleton('sales/order_config')->getStatuses();
        }
        $options = array();
        $options[] = array(
            'value' => '',
            'label' => Mage::helper('adminhtml')->__('-- Please Select --')
        );
        foreach ($statuses as $code => $label) {
            $options[] = array(
                'value' => $code,
                'label' => $label
            );
        }
        return $options;
    }

}