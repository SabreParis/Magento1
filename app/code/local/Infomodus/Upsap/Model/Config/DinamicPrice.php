<?php
/**
 * Created by PhpStorm.
 * User: Vitalij
 * Date: 19.06.15
 * Time: 0:21
 */

class Infomodus_Upsap_Model_Config_DinamicPrice
{
    public function toOptionArray()
    {
        return array(
            array('label' => 'Static', 'value' => 0),
            array('label' => 'UPS', 'value' => 1),
        );
    }
}