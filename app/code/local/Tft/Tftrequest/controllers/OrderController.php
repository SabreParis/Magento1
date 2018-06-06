<?php 
class Tft_Tftrequest_OrderController extends Mage_Core_Controller_Front_Action
{
	private $obj_op;
	
	protected function _construct()
	{
		set_error_handler(array($this,'errorHandler'));
		$this->obj_op = Mage::getModel('tftorders/orderprocess',NULL);
	}
	public function indexAction(){
       $this->loadLayout(false);
    }
	public function receiveAction()
	{
		if($this->obj_op->checkIP()) // check IP
		{
			$request_data = file_get_contents("php://input");
			if($request_data)
			{
			 	  $err=0;
				  try
				  {
					  $this->obj_op->tftLog($request_data,NULL,'tftorders.log');
					  $order_xml = simplexml_load_string($request_data);
				  }
				  catch(Exception $e)
				  {
					 $err=1;
					 $res=$this->obj_op->ordResponseMessage("wrongxml");
				  }
				  if($err!=1)
				  {
					$_orderProcess = Mage::getModel('tftorders/orderprocess',$order_xml);
					$res=$_orderProcess->order_response();
					$this->obj_op->tftLog($res,NULL,'tftorders.log');
				  }
			}
			else
			{
				$res=$this->obj_op->ordResponseMessage("badrequest");
			}
		}
		else // IF nad request
		{
			$res=$this->obj_op->ordResponseMessage("nopermission");
		}
		$xml_head='<?xml version="1.0" encoding="utf-8" ?>';
		$send_response=$xml_head.$res;
		$this->getResponse()->setHeader('Content-type', 'text/xml');
		$this->getResponse()->setBody($send_response);
	}
	public function errorHandler( $errno, $errstr, $errfile, $errline, $errcontext)
	{
	   $resval= '<![CDATA[ ('.$errno.') '.$errstr.' - file name : '.$errfile.'  - Line No : '.$errline."]]>";
	   Mage::log($resval,NULL,'tfterror.log',true);
	}
}
?>