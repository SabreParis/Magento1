<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 04/02/2016
 * Time: 12:18
 */
class Sabre_Configuration_Model_Adminhtml_System_Config_Source_Order_Status_AcceptPayment extends Sabre_Configuration_Model_Adminhtml_System_Config_Source_Order_Status
{
    // set null to enable all possible
    protected $_stateStatuses = array(
        Mage_Sales_Model_Order::STATE_NEW,
        Mage_Sales_Model_Order::STATE_PROCESSING,
    );
}