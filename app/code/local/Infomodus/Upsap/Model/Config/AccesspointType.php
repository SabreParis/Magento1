<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Owner
 * Date: 16.12.11
 * Time: 10:55
 * To change this template use File | Settings | File Templates.
 */
class Infomodus_Upsap_Model_Config_AccesspointType
{
    public function toOptionArray()
    {
        $c = array(
            array('label' => Mage::helper('upsap')->__('Hold for Pickup at UPS Access Point'), 'value' => '01'),
            array('label' => Mage::helper('upsap')->__('UPS Access Point Delivery'), 'value' => '02'),
        );
        return $c;
    }
}