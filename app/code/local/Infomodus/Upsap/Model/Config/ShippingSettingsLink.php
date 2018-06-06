<?php
/**
 * Created by PhpStorm.
 * User: Vitalij
 * Date: 01.10.14
 * Time: 11:55
 */
class Infomodus_Upsap_Model_Config_ShippingSettingsLink
{
    public function getCommentText()
    {
        return '<a href="'.Mage::helper("adminhtml")->getUrl("adminhtml/upsap_method/index").'" target="_blank">'.Mage::helper('adminhtml')->__("Add more shipping methods").'</a>';
    }
}