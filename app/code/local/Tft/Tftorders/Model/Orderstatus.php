<?php
class Tft_Tftorders_Model_Orderstatus
{
	private $orderId;
	private $orderStatus;
	private $totshipment;
	private $carrier_name = array();
	private $shipping_number = array();
	private $delivery_date = array();
	private $shippedItems = array();
	
	public function __construct($oid)
	{
		$this->orderId=$oid;
	}
	public function getOrdStatus()
	{
		$collection = Mage::getModel('sales/order')->getCollection()->addAttributeToFilter('entity_id',$this->orderId);
		$order = $collection->getLastItem();
		
	     if ($order->getId()) { 
    		
			$this->orderStatus=$order->getStatusLabel();	
			
				$this->totshipment=0;
				$shipmentCollection = Mage::getResourceModel('sales/order_shipment_collection')->setOrderFilter($this->orderId)->load();
				if(count($shipmentCollection )>=1)
				{
					 foreach ($shipmentCollection as $shipment)
					 {
						$this->totshipment=$this->totshipment+1;
					   // This will give me the shipment IncrementId, but not the actual tracking information.
						foreach($shipment->getAllTracks() as $tracknum)
						{
							$this->shipping_number[]=$tracknum->getNumber();
							$this->carrier_name[] = $tracknum->getTitle();
							break;
						}
						if(count($shipment->getAllTracks())==0)
						{
							$this->shipping_number[]='';
							$this->carrier_name[]= '';	
						}
						$this->shippedItems[] = $shipment->getItemsCollection();
						$this->delivery_date[]=$shipment->getCreatedAt();
					  }
				}
				  
	     }
	}
	
	public function orderstatus_response()
	{
		$res_xml='<response>';
			$res_xml.='
			<orderstatus>
					<order_id>'.$this->orderId.'</order_id>
					<order_state>'.$this->orderStatus.'</order_state>';
					
					if($this->totshipment>=1)
					{
						$res_xml.='<shipments>';
						for($s=0;$s<$this->totshipment;$s++)
						{
							$res_xml.='<shipment>
								<carrier_name>'.$this->carrier_name[$s].'</carrier_name>
								<delivery_date>'.$this->delivery_date[$s].'</delivery_date>
								<shipping_number>'.$this->shipping_number[$s].'</shipping_number>
								<pids>';
									foreach ($this->shippedItems[$s] as $item) {
												$res_xml.='<pid>'.$item["product_id"].'</pid>';
									}
						  $res_xml.='</pids>
							</shipment>';
						}
						$res_xml.='</shipments>';
					}
      		$res_xml.='</orderstatus>';
		$res_xml.='
			</response>';
		return $res_xml;
	}
}