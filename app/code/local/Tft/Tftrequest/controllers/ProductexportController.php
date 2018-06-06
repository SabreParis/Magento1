<?php
class Tft_Tftrequest_ProductexportController extends Mage_Core_Controller_Front_Action {
	
	public function exportsAction()
	{
		set_time_limit(1800);
        ini_set('memory_limit', '1024M');
		$obj_exproducts = Mage::getModel('tftexports/exportproducts');
		$response=$obj_exproducts->getProducts();
		$this->getResponse()->setHeader('Content-type', 'text/xml');
		$this->getResponse()->setBody($response);
	}
}
?>