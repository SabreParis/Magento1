<?php
class Tft_Tftrequest_ProductController extends Mage_Core_Controller_Front_Action
{
    public function requestAction(){
			 echo "Respect my authoritah";
			 exit(0);
    }
	public function helloAction(){
	header("Content-type:text/xml; charset=utf-8"); 
	echo '<?xml version="1.0" encoding="utf-8" ?>';
	$res="<response>
				<status_code>0</status_code>
				<status_message>You have no any permission to send request.</status_message>
			  </response>";
	echo $res;
    }
}
?>