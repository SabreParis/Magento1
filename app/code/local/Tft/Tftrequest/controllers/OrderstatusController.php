<?php
class Tft_Tftrequest_OrderstatusController extends Mage_Core_Controller_Front_Action
{
    public function getstatusAction(){
		
		$this->getResponse()->setHeader('Content-type', 'text/xml');
		$res='<?xml version="1.0" encoding="utf-8" ?>';
		
		if(isset($_REQUEST["orderid"]) && $_REQUEST["orderid"]!='')
		{
			$oid=explode("-",$_REQUEST["orderid"]);
					
			$_orderStatus = Mage::getModel('tftorders/orderstatus',$oid[0]);
			$_orderStatus->getOrdStatus();
			$res.=$_orderStatus->orderstatus_response();
			$this->getResponse()->setBody($res);
		}
		else
		{
			$res='<response><orderstatus>0</orderstatus></response>';
			$this->getResponse()->setBody($res);
		}
    }
	
}
?>