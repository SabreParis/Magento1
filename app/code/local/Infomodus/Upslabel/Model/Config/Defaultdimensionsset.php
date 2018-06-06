<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Owner
 * Date: 16.12.11
 * Time: 10:55
 * To change this template use File | Settings | File Templates.
 */
class Infomodus_Upslabel_Model_Config_Defaultdimensionsset
{
    public function toOptionArray()
    {
        /*multistore*/
        $storeId = Mage::app()->getRequest()->getParam('store', NULL);
        if($storeId && Mage::getConfig()->getNode('default/upslabel/myoption/multistore/active') == 1){
            $code = Mage::helper('upslabel/help')->getStoreByCode($storeId);
            if($code){
                $storeId = $code->getId();
            }
        }
        /*multistore*/

        $c = array();
        $c[] = array('label' => Mage::helper('adminhtml')->__('Custom dimensions'), 'value' => 0);
        for($i=1; $i<=15; $i++){
        if(Mage::getStoreConfig('upslabel/dimansion_'.$i.'/enable', $storeId)==1){
            $c[] = array('label' => Mage::helper('adminhtml')->__(Mage::getStoreConfig('upslabel/dimansion_'.$i.'/dimansionname', $storeId)), 'value' => $i);
        }
        }
        return $c;
    }
}