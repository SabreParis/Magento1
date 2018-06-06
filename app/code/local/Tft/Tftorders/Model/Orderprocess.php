<?php
class Tft_Tftorders_Model_Orderprocess
{
	private $tft_order_number;
	private $order_marketplace_source;
	private $order_date;
	private $status;
	private $customer_email;
	private $customer_firstname;
	private $customer_lastname;
	
	private $billing_city;
	private $billing_country;
	private $billing_firstname;
	private $billing_lastname;
	private $billing_address1;
	private $billing_address2;
	private $billing_pincode;
	private $billing_phone;
	
	private $shipping_city;
	private $shipping_country;
	private $shipping_firstname;
	private $shipping_lastname;
	private $shipping_address1;
	private $shipping_address2;
	private $shipping_pincode;
	private $shipping_phone;
	
	private $tft_product_list = array();

	private $simple_order_elements;
	private $error_code = array();
	private $error_messages = array();
	private $order_messages = array();
	
	/* Magento variables */
	private $id_customer;
	private $id_billing_address;
	private $id_shipping_address;
	private $id_quote;
	private $id_order;
	
	private $read;
	private $write;
	private $tftorders_ip_address;

	public function __construct(SimpleXMLElement $order_xml = null)
	{
		$this->simple_order_elements=$order_xml;
		
		//Get IP adddress
		$this->tftorders_ip_address = Mage::getStoreConfig('tftorders/tftorders_group/tftorders_ip_address');
		
		//database read adapter 
		$this->read = Mage::getSingleton('core/resource')->getConnection('core_read'); 
		 
		//database write adapter 
		$this->write = Mage::getSingleton('core/resource')->getConnection('core_write');
 
		//echo $this->count_orders();
		$this->process_order();
	}
	public function process_order()
	{
		$cid='';
		if($this->chkModuleSettings()) // Check module setting
		{
			$tot_orders=$this->count_orders();
			if($tot_orders>=1)
			{
				for($ord=0;$ord<$tot_orders;$ord++)
				{
					if($this->set_values($ord)) 
					{
						// NOW ORDER PRECESS START
						try
						{
						   if($this->order_marketplace_source!='') // Brnad Marketplace source check
						   {
							   $this->id_order=$this->checkOrderDuplicate($this->tft_order_number);
							   if($this->id_order)
							   {
								   $this->addOrderMessage($this->tft_order_number,$this->id_order,1,"Order already exists");
								   continue;
							   }
							   else
							   {
									### Set order id 0
									$this->id_order=0;
									
									###Customer Create
									if(Mage::getStoreConfig('tftorders/tftorders_group/tftorders_create_customer')==1)
									{
										$this->id_customer=$this->getOrAddCustomer();
									}
									else
									{
										$id_customer_array = Mage::getModel('customer/customer')->load(Mage::getStoreConfig('tftorders/tftorders_group/tftorders_customer_id')); // cust ID
										$id_customer=$id_customer_array['entity_id'];
										if($id_customer!='' && $id_customer!=0)
										{
											$this->id_customer= Mage::getStoreConfig('tftorders/tftorders_group/tftorders_customer_id');
										}
										else
										{
											$this->id_customer=0;
											$this->addOrderMessage($this->tft_order_number,$this->id_order,0,"Your customer ID not exists. Please change it on your configuration file.");
										}
									}
									
									if($this->id_customer!='' && $this->id_customer!=0) // Check Customer Id
									{
										###Billing address create
										$this->id_billing_address=$this->addBillingAddress();
										
										if($this->id_billing_address!='' && $this->id_billing_address!=0) // Check Billing Address
										{
											###Shipping address create
											$this->id_shipping_address=$this->addShippingAddress();
											if($this->id_shipping_address!='' && $this->id_shipping_address!=0) // Check Shipping Address
											{
													###Caart and its product create
													$this->id_cart=$this->addCart();
													
													if($this->id_cart!='' && $this->id_cart!=0) // Check Shipping Address
													{
														###order create
														$this->id_order=$this->addOrder();
														
														if($this->id_order)
														{
															$this->addOrderMessage($this->tft_order_number,$this->id_order,1,"Order created successfully");
															continue;
														}
														else
														{
															$this->addOrderMessage($this->tft_order_number,0,0,"Process failed, Order not created.");
															continue;
														}
													}
													else
													{
														continue;
													}
											}
											else
											{
												continue;
											}
										}
										else
										{
											continue;
										}
									}
									else
									{
										continue;
									}
							   }
						   }
						   else
						   {
								$this->addOrderMessage($this->tft_order_number,$this->id_order,0,"Process failed, Order not created because Marketplace source field blank.");
								continue;	
						   }
						}
						catch(Exception $e)
						{
							$this->addOrderMessage($this->tft_order_number,$this->id_order,0,$e->getMessage());
							continue;
						}
					}
				}
			}
			else
			{
				$this->addErrorMessage(0,"No any order(s) found in XML!");
				return false;
			}
		}
	}
	
	public function count_orders()
	{
		if(isset($this->simple_order_elements->orders->order))
		{
			return count($this->simple_order_elements->orders->order);
		}
	}
	public function set_values($ordindexid)
	{
		$cur_order=$this->simple_order_elements->orders->order[$ordindexid]; // Set Current Order Index
		
		//Set Values to private variable
		$this->tft_order_number=(string)$cur_order->tft_order_number;
		$this->order_marketplace_source=(string)$cur_order->order_marketplace_source;
		$this->order_date=(string)$cur_order->order_date;
		$this->status=(string)$cur_order->status;
	
		$this->customer_email=(string)$cur_order->customer->email;
		$this->customer_firstname=(string)$cur_order->customer->firstname;
		$this->customer_lastname=(string)$cur_order->customer->lastname;
	
		$this->billing_city=(string)$cur_order->customer->billing_address->city;
		$this->billing_country=(string)$cur_order->customer->billing_address->country_iso;
		$this->billing_firstname=(string)$cur_order->customer->billing_address->firstname;
		$this->billing_lastname=(string)$cur_order->customer->billing_address->lastname;
		$this->billing_address1=(string)$cur_order->customer->billing_address->address_1;
		$this->billing_address2=(string)$cur_order->customer->billing_address->address_2;
		$this->billing_pincode=(string)$cur_order->customer->billing_address->zip_code;
		$this->billing_phone=(string)$cur_order->customer->billing_address->phone;
	
		$this->shipping_city=(string)$cur_order->customer->shipping_address->city;
		$this->shipping_country=(string)$cur_order->customer->shipping_address->country_iso;
		$this->shipping_firstname=(string)$cur_order->customer->shipping_address->firstname;
		$this->shipping_lastname=(string)$cur_order->customer->shipping_address->lastname;
		$this->shipping_address1=(string)$cur_order->customer->shipping_address->address_1;
		$this->shipping_address2=(string)$cur_order->customer->shipping_address->address_2;
		$this->shipping_pincode=(string)$cur_order->customer->shipping_address->zip_code;
		$this->shipping_phone=(string)$cur_order->customer->shipping_address->phone;
		
		// Set Products
		$tot_cart_product=count($cur_order->order_line_items->order_line);
		unset( $this->tft_product_list ); 
		$this->tft_product_list =array();
		for($c=0;$c<$tot_cart_product;$c++)
		{
			$this->tft_product_list[] = array(
				'product_id_ref' => (string)$cur_order->order_line_items->order_line[$c]->id_product,
				'quantity' => (string)$cur_order->order_line_items->order_line[$c]->quantity,
				'item_total' => (string)$cur_order->order_line_items->order_line[$c]->item_total,
				'item_shipment' => (string)$cur_order->order_line_items->order_line[$c]->item_shipment
			);
		}
		return true;
	}
	
	### get or create customer
	public function getOrAddCustomer()
	{
		if($this->customer_lastname!='' && $this->customer_firstname!='' && $this->customer_email!='' )
		{
			try
			{
				//$websiteId = Mage::app()->getWebsite()->getId();
				//$store = Mage::app()->getStore();
				$websiteId = Mage::getStoreConfig('tftorders/tftorders_group/tftorders_website_id');
				$storeId = Mage::getStoreConfig('tftorders/tftorders_group/tftorders_store_id');
				$store = Mage::app()->setCurrentStore($storeId);
				
				$customer = Mage::getModel("customer/customer");
				$customer->setWebsiteId($websiteId);
				$customer->loadByEmail($this->customer_email);
				if(!$customer->getId())  // Check Customer Exists
				{
						try{
					$customer->setWebsiteId($websiteId)
								->setFirstname($this->customer_firstname)
								->setLastname($this->customer_lastname)
								->setEmail($this->customer_email)
								->setPassword(md5($this->customer_firstname.date("Y")));
					 
							$customer->save();
							$this->id_customer=$customer->getId();
						}
						catch (Exception $e) {
							$this->addOrderMessage($this->tft_order_number,0,0,"Customer Creation: ".$e->getMessage());
							return false;
						}
				}
				else
				{
					$this->id_customer=$customer->getId();
				}
			}
			catch (Exception $e) {
				$this->addOrderMessage($this->tft_order_number,0,0,"Customer Creation: ".$e->getMessage());
				return false;
			}
		}
		else
		{
			$this->addOrderMessage($this->tft_order_number,0,0,"Process failed, Customer not created.");
			return false;
		}
		return $this->id_customer;
	}
	
	### Add Billing Address
	
	public function addBillingAddress()
	{
		if($this->id_customer!='' && $this->billing_country!='' && $this->billing_address1!='' && $this->billing_pincode && $this->billing_city)
		{
			$address = Mage::getModel("customer/address");
			$address->setCustomerId($this->id_customer)
					->setFirstname(preg_replace('/[0-9]+/','',$this->billing_firstname))
					->setLastname(preg_replace('/[0-9]+/','',$this->billing_lastname))
					->setCountryId($this->billing_country)
					->setPostcode($this->billing_pincode)
					->setCity($this->billing_city)
					->setTelephone($this->billing_phone)
					->setStreet($this->billing_address1)
					->setIsDefaultBilling('1')
					->setSaveInAddressBook('1');
			 
			try{
				$address->save();
				$this->id_billing_address=$address->getId();
			}
			catch (Exception $e) {
				$this->addOrderMessage($this->tft_order_number,0,0,"Billing Address Creation: ".$e->getMessage());
				return false;
			}
			return $this->id_billing_address;
		}
		else
		{
			$this->addOrderMessage($this->tft_order_number,0,0,"Process failed, Billing address not created.");
			return false;
		}
	}
	
	### Add Shipping Address
	
	public function addShippingAddress()
	{
		if($this->id_customer!='' && $this->shipping_country!='' && $this->shipping_address1!='' && $this->shipping_pincode && $this->shipping_city)
		{
			$address = Mage::getModel("customer/address");
			$address->setCustomerId($this->id_customer)
					->setFirstname(preg_replace('/[0-9]+/','',$this->shipping_firstname))
					->setLastname(preg_replace('/[0-9]+/','',$this->shipping_lastname))
					->setCountryId($this->shipping_country)
					->setPostcode($this->shipping_pincode)
					->setCity($this->shipping_city)
					->setTelephone($this->shipping_phone)
					->setStreet($this->shipping_address1)
					->setIsDefaultShipping('1')
					->setSaveInAddressBook('1');
			try{
				$address->save();
				$this->id_shipping_address=$address->getId();
			}
			catch (Exception $e) {
				$this->addOrderMessage($this->tft_order_number,0,0,"Shipping Address Creation: ".$e->getMessage());
				return false;
			}
			return $this->id_shipping_address;
		}
		else
		{
			$this->addOrderMessage($this->tft_order_number,0,0,"Process failed, Shipping address not created.");
			return false;
		}
	}
	
	public function addCart()
	{
		if($this->checkProductsinCart())
		{
			try 
			{
				$customerObj = Mage::getModel('customer/customer')->load($this->id_customer);
				$storeId = $customerObj->getStoreId();
				$quoteObj = Mage::getModel('sales/quote')->assignCustomer($customerObj); //sets ship/bill address
				$storeObj = $quoteObj->getStore()->load($storeId);
				$quoteObj->setStore($storeObj);
				$productModel = Mage::getModel('catalog/product');
				$pModel = Mage::getModel('catalog/product');
				
				for($i=0;$i<count($this->tft_product_list);$i++)
				{
					if(Mage::getStoreConfig('tftorders/tftorders_group/tftorders_ref_type')=="id") // Check Product id
					{
						$_product=$pModel->load($this->tft_product_list[$i]["product_id_ref"]);
						if($_product->getId())
						{
							$pid=$this->tft_product_list[$i]["product_id_ref"];
						}
					}
					else // Check product sku
					{
						$_sku = $this->tft_product_list[$i]["product_id_ref"];
						$_productId = $pModel->getIdBySku($_sku);
						$_product=$pModel->load($_productId);
						if($_product->getId())
						{
							$pid=$this->tft_product_list[$i]["product_id_ref"];
						}	
					}
					
					try {
						$quoteItem = Mage::getModel('sales/quote_item')->setProduct($_product);
					} 
					catch (Exception $e) {
						$this->addOrderMessage($this->tft_order_number,0,0,"Cart Product Creation: ".$e->getMessage());
						return false;
					}
					$quoteItem->setQuote($quoteObj);
					$quoteItem->setQty($this->tft_product_list[$i]["quantity"]);
					
					$quoteObj->addItem($quoteItem);
				}
			   
				$quoteObj->collectTotals();
				$quoteObj->save();
				
				$this->id_quote = $quoteObj->getId();
			}
			catch (Exception $e) {
				$this->addOrderMessage($this->tft_order_number,0,0,"Cart Creation: ".$e->getMessage());
				return false;
			}
			return $this->id_quote;
		}
	}
	public function checkProductsinCart()
	{
		$pModel = Mage::getModel('catalog/product');
		for($i=0;$i<count($this->tft_product_list);$i++)
		{
			$pid='';
			if(Mage::getStoreConfig('tftorders/tftorders_group/tftorders_ref_type')=="id") // Check Product id
			{
				$_product=$pModel->load($this->tft_product_list[$i]["product_id_ref"]);
				if($_product->getId())
				{
					$pid=$this->tft_product_list[$i]["product_id_ref"];
				}
			}
			else // Check product sku
			{
				$_sku = $this->tft_product_list[$i]["product_id_ref"];
				$_productId = $pModel->getIdBySku($_sku);
				$_product=$pModel->load($_productId);
				if($_product->getId())
				{
					$pid=$this->tft_product_list[$i]["product_id_ref"];
				}	
			}
			if($pid=='') // Product ID / Reference Not Found
			{
				$this->addOrderMessage($this->tft_order_number,$this->id_order,0,"Process failed,Cart not created because product id/reference not found");				return false;
			}
			else
			{
				// Check Product Qty
					if($this->chkOutOfStockOrder($_product)<=0) // check Deny out of stock order
					{
						$this->addOrderMessage($this->tft_order_number,$this->id_order,0,"Process failed,Cart not created because product is out of stock");						return false;
					}
			}
		}
		return true;	
	}
	public function chkOutOfStockOrder($_product)
	{
		$stock=(int) Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product)->getQty();
		return $stock;
	}
	public function addOrder()
	{
		try
		{
			$hpc_connector_orderid = $this->tft_order_number;
			$hpc_connector_sitename = 'tft'; 
			
			//methods: authorizenet, paypal_express, googlecheckout, purchaseorder
			$hpc_payment_method = 'tftorders';
		
			//methods: flatrate_flatrate, freeshipping_freeshipping
			$_shipping_code = Mage::getStoreConfig('tftorders/tftorders_group/tftorders_shipping_methods');
			if(!$_title = Mage::getStoreConfig("carriers/$_shipping_code/title"))
				$_title = $_shipping_code;
				
			$hpc_shipping_method = Mage::getStoreConfig('tftorders/tftorders_group/tftorders_shipping_methods');
			$hpc_shipping_method_description = $_shipping_code;
		
			$quoteObj = Mage::getModel('sales/quote')->load($this->id_quote);
			$items = $quoteObj->getAllItems();
			$quoteObj->collectTotals();
			$quoteObj->reserveOrderId();
		
			$quotePaymentObj = $quoteObj->getPayment();
			//methods: authorizenet, paypal_express, googlecheckout, purchaseorder
			$quotePaymentObj->setMethod($hpc_payment_method);
		
			$quoteObj->setPayment($quotePaymentObj);
			$convertQuoteObj = Mage::getSingleton('sales/convert_quote');
		
			$orderObj = $convertQuoteObj->addressToOrder($quoteObj->getShippingAddress());
		
		
			$orderPaymentObj = $convertQuoteObj->paymentToOrderPayment($quotePaymentObj);
		
			$orderObj->setBillingAddress($convertQuoteObj->addressToOrderAddress($quoteObj->getBillingAddress()));
		
			//annet -pk to set shipping method
			// $orderObj->setShippingAddress($convertQuoteObj->addressToOrderAddress($quoteObj->getShippingAddress()));
			$orderObj->setShippingAddress($convertQuoteObj->addressToOrderAddress($quoteObj->getShippingAddress()))
					->setShipping_method($hpc_shipping_method)
					->setShippingDescription($hpc_shipping_method_description);
		
			$orderObj->setPayment($convertQuoteObj->paymentToOrderPayment($quoteObj->getPayment()));
		
			$orderObj->setHpcOrderId($hpc_connector_orderid);
			$orderObj->setHpcOrderFrom($hpc_connector_sitename);
		
			foreach ($items as $item) {
				//@var $item Mage_Sales_Model_Quote_Item
				$orderItem = $convertQuoteObj->itemToOrderItem($item);
				if ($item->getParentItem()) {
					$orderItem->setParentItem($orderObj->getItemByQuoteItemId($item->getParentItem()->getId()));
				}
				Mage::getSingleton('cataloginventory/stock')->registerItemSale($orderItem);
				$orderObj->addItem($orderItem);
			}
			$orderObj->setCanShipPartiallyItem(false);
		
			$totalDue = $orderObj->getTotalDue();
			 //$orderObj->sendNewOrderEmail();
		
			$orderObj->place(); //calls _placePayment
			$orderObj->save();
		
			$orderId = $orderObj->getId();
			//   return $orderId;
		
			$orderObj->load(Mage::getSingleton('sales/order')->getLastOrderId());
			$lastOrderId = $orderObj->getIncrementId();
			
			$ord_table=$this->getMageTable("sales_flat_order");
			$update_ord="update ".$ord_table." SET  tft_from='1',tft_order_number='".$this->tft_order_number."',tft_order_marketplace_source='".$this->order_marketplace_source."' where entity_id='".$orderId."'";
			$this->write->query($update_ord);
		
			$this->updateTFTPrice($orderId);
			
			return $orderId."-".$lastOrderId;
		}
		catch (Exception $e) {
				$this->addOrderMessage($this->tft_order_number,0,0,"Order Creation: ".$e->getMessage());
				return false;
		}
	}
	public function updateTFTPrice($ordid)
	{
		$tftorders_item_tax_rate=Mage::getStoreConfig('tftorders/tftorders_group/tftorders_item_tax_rate');
		$tftorders_carrier_tax_rate=Mage::getStoreConfig('tftorders/tftorders_group/tftorders_carrier_tax_rate');
		
		// Update Order Items
		$pModel = Mage::getModel('catalog/product');
		$pid=0;
		for($i=0;$i<count($this->tft_product_list);$i++)
		{
			if(Mage::getStoreConfig('tftorders/tftorders_group/tftorders_ref_type')=="id") // Check Product id
			{
				$_product=$pModel->load($this->tft_product_list[$i]["product_id_ref"]);
				if($_product->getId())
				{
					$pid=$this->tft_product_list[$i]["product_id_ref"];
				}
			}
			else // Check product sku
			{
				$_sku = $this->tft_product_list[$i]["product_id_ref"];
				$_productId = $pModel->getIdBySku($_sku);
				$_product=$pModel->load($_productId);
				if($_product->getId())
				{
					$pid=$this->tft_product_list[$i]["product_id_ref"];
				}	
			}
			
				$itm_price=round(round($this->tft_product_list[$i]["item_total"]-(((int)$tftorders_item_tax_rate*$this->tft_product_list[$i]["item_total"])/(100+(int)$tftorders_item_tax_rate)),2)/$this->tft_product_list[$i]["quantity"],2);
				$itm_base_price=$itm_price;
				$itm_original_price=$itm_price;
				$itm_base_original_price=$itm_price;
				$itm_tax_percent=$tftorders_item_tax_rate;
				$itm_tax_amount=round($this->tft_product_list[$i]["item_total"]-($itm_price*$this->tft_product_list[$i]["quantity"]),2);
				$itm_base_tax_amount=$itm_tax_amount;
				$itm_discount_percent=0;
				$itm_discount_amount=0;
				$itm_base_discount_amount=0;
				$itm_row_total=round($itm_price*$this->tft_product_list[$i]["quantity"],2);
				$itm_base_row_total=$itm_row_total;
				$itm_base_tax_before_discount=0;
				$itm_tax_before_discount=0;
				$itm_price_incl_tax=round($itm_price+($itm_tax_amount/$this->tft_product_list[$i]["quantity"]),2);
				$itm_base_price_incl_tax=$itm_price_incl_tax;
				$itm_row_total_incl_tax=round($this->tft_product_list[$i]["item_total"],2);
				$itm_base_row_total_incl_tax=$itm_row_total_incl_tax;
				
				$ord_item_table=$this->getMageTable("sales_flat_order_item");
				$update_item="update ".$ord_item_table." SET price='".$itm_price."',base_price='".$itm_base_price."',original_price='".$itm_original_price."',base_original_price='".$itm_base_original_price."',tax_percent='".$itm_tax_percent."',tax_amount='".$itm_tax_amount."',base_tax_amount='".$itm_base_tax_amount."',discount_percent='".$itm_discount_percent."',discount_amount='".$itm_discount_amount."',base_discount_amount='".$itm_base_discount_amount."',row_total='".$itm_row_total."',base_row_total='".$itm_base_row_total."',base_tax_before_discount='".$itm_base_tax_before_discount."',tax_before_discount='".$itm_tax_before_discount."',price_incl_tax='".$itm_price_incl_tax."',base_price_incl_tax='".$itm_base_price_incl_tax."',row_total_incl_tax='".$itm_row_total_incl_tax."',base_row_total_incl_tax='".$itm_base_row_total_incl_tax."' where order_id='".$ordid."' AND product_id='".$pid."'";
				$this->write->query($update_item);
		}
		
		// Update Order
		
		$total_products=0;
		$total_shipping=0;
		
		for($i=0;$i<count($this->tft_product_list);$i++)
		{
			$total_products=$total_products+$this->tft_product_list[$i]["item_total"];
			$total_shipping=$total_shipping+$this->tft_product_list[$i]["item_shipment"];
		}	
		
		$o_base_discount_amount=0;
		$o_base_discount_canceled=0;
		$o_base_grand_total=round($total_products+$total_shipping,2);
		$o_base_shipping_tax_amount=round($total_shipping-round($total_shipping-($tftorders_carrier_tax_rate*$total_shipping/(100+$tftorders_carrier_tax_rate)),2),2);
		$o_base_shipping_amount=round($total_shipping-$o_base_shipping_tax_amount,2);
		$o_base_subtotal=round($total_products-(($tftorders_item_tax_rate*$total_products)/(100+$tftorders_item_tax_rate)),2);
		$o_base_tax_amount=round($total_products-$o_base_subtotal,2)+$o_base_shipping_tax_amount;
		$o_discount_amount=0;
		$o_grand_total=$o_base_grand_total;
		$o_shipping_amount=$o_base_shipping_amount;
		$o_shipping_tax_amount=$o_base_shipping_tax_amount;
		$o_subtotal=$o_base_subtotal;
		$o_tax_amount=$o_base_tax_amount;
		$o_base_subtotal_incl_tax=round($total_products,2);
		$o_base_shipping_incl_tax=round($total_shipping,2);
		
		$ord_order_table=$this->getMageTable("sales_flat_order");
		$update_order="update ".$ord_order_table." SET base_discount_amount='".$o_base_discount_amount."',base_discount_canceled='".$o_base_discount_canceled."',base_grand_total='".$o_base_grand_total."',base_shipping_amount='".$o_base_shipping_amount."',base_shipping_tax_amount='".$o_base_shipping_tax_amount."',base_subtotal='".$o_base_subtotal."',base_tax_amount='".$o_base_tax_amount."',discount_amount='".$o_discount_amount."',grand_total='".$o_grand_total."',shipping_amount='".$o_shipping_amount."',shipping_tax_amount='".$o_shipping_tax_amount."',subtotal='".$o_subtotal."',tax_amount='".$o_tax_amount."',base_shipping_discount_amount='".$o_discount_amount."',shipping_discount_amount='".$o_discount_amount."',base_subtotal_incl_tax='".$o_base_subtotal_incl_tax."',subtotal_incl_tax='".$o_base_subtotal_incl_tax."',shipping_incl_tax='".$o_base_shipping_incl_tax."',base_shipping_incl_tax='".$o_base_shipping_incl_tax."' where entity_id='".$ordid."'";
		$this->write->query($update_order);
		
		// Update Grid
		$og_base_grand_total=round($total_products+$total_shipping,2);
		$og_grand_total=round($total_products+$total_shipping,2);
		
		$ord_grid_table=$this->getMageTable("sales_flat_order_grid");
		$update_grid="update ".$ord_grid_table." SET base_grand_total='".$og_base_grand_total."',grand_total='".$og_grand_total."' where entity_id='".$ordid."'";
		$this->write->query($update_grid);
		
		// Update Order Payment
		$op_base_shipping_amount=round($total_shipping,2);
		$op_shipping_amount=round($total_shipping,2);
		$op_base_amount_ordered=round($total_products,2);
		$op_amount_ordered=round($total_products,2);

		$ord_payment_table=$this->getMageTable("sales_flat_order_payment");
		$update_payment="update ".$ord_payment_table." SET base_shipping_amount='".$op_base_shipping_amount."',shipping_amount='".$op_shipping_amount."',base_amount_ordered='".$op_base_amount_ordered."',amount_ordered='".$op_amount_ordered."' where parent_id='".$ordid."'";
		$this->write->query($update_payment);
		
	}
	public function chkModuleSettings()
	{
		$_blk=true;
		$errmsg="Configuration field setting remaining or incorrect ";
		
		if(Mage::getStoreConfig('tftorders/tftorders_group/tftorders_ip_address')=="") 
		{
			$errmsg.=" [IP Address] ";
			$_blk=false;			
		}
		if(Mage::getStoreConfig('tftorders/tftorders_group/tftorders_ref_type')=="") 
		{
			$errmsg.=" [Product Ref. Type] ";
			$_blk=false;			
		}
		if((int)Mage::getStoreConfig('tftorders/tftorders_group/tftorders_store_id')==0) 
		{
			$errmsg.=" [Store Id] ";
			$_blk=false;			
		}
		else
		{
			$storeid_tbl=$this->getMageTable("core_store");
			$sel_storeid="SELECT count(store_id) as w from ".$storeid_tbl." where store_id=".(int)Mage::getStoreConfig('tftorders/tftorders_group/tftorders_store_id')."";
			$totstore = $this->read->fetchOne($sel_storeid);
			if($totstore<=0)
			{
				$errmsg.=" [Store Id] ";
				$_blk=false;			
			}
		}
		
		if((int)Mage::getStoreConfig('tftorders/tftorders_group/tftorders_website_id')==0) 
		{
			$errmsg.=" [Website Id] ";
			$_blk=false;			
		}
		else
		{
			$websiteid_tbl=$this->getMageTable("core_website");
			$sel_websiteid="SELECT count(website_id) as w from ".$websiteid_tbl." where website_id=".(int)Mage::getStoreConfig('tftorders/tftorders_group/tftorders_website_id')."";
			$totwebsite = $this->read->fetchOne($sel_websiteid);
			if($totwebsite<=0)
			{
				$errmsg.=" [Website Id] ";
				$_blk=false;			
			}
		}
		if(Mage::getStoreConfig('tftorders/tftorders_group/tftorders_create_customer')=="") 
		{
			$errmsg.=" [Always Create Customer] ";
			$_blk=false;			
		}
		if(Mage::getStoreConfig('tftorders/tftorders_group/tftorders_create_customer')==0)
		{
			if(Mage::getStoreConfig('tftorders/tftorders_group/tftorders_customer_id')==0) 
			{
				$errmsg.=" [Customer Id] ";
				$_blk=false;			
			}
		}
		if(Mage::getStoreConfig('tftorders/tftorders_group/tftorders_shipping_methods')=="") 
		{
			$errmsg.=" [Shipping Method] ";
			$_blk=false;			
		}
		if($_blk==true)
		{
			return true;
		}
		else
		{
			$this->addErrorMessage("0",$errmsg);
			return false;
		}
	}
	public function addErrorMessage($ecode,$message)
	{
		$this->error_code[] = $ecode;
		$this->error_messages[] = $message;
	}
	public function addOrderMessage($tft_order_number,$return_order_number,$status_code,$status_message)
	{
		$this->order_messages[] = array(
			'tft_order_number' => $tft_order_number,
			'return_order_number' => $return_order_number,
			'status_code' => $status_code,
			'status_message' => $status_message
		);
	}
	public function getMageTable($tblname)
	{
		return Mage::getSingleton('core/resource')->getTableName($tblname);
	}
	public function checkOrderDuplicate($tftordderid)
	{
		$ordertid_tbl=$this->getMageTable("sales_flat_order");
		$sel_ordertid="SELECT entity_id from ".$ordertid_tbl." where tft_order_number='".$tftordderid."' LIMIT 1";
		$ordertid = $this->read->fetchOne($sel_ordertid);
		return $ordertid;
	}
	
	public function order_response()
	{
		$res_xml='<response>';
		if(count($this->error_messages)>=1)
		{
			$res_xml.='
			<status_code>0</status_code>
			<status_message>';
			
			for($i=0;$i<count($this->error_messages);$i++)
			{
				$res_xml.=$this->error_messages[$i]."\n\n";
			}
			$res_xml.='</status_message>';
		}
		if(count($this->order_messages)>=1)
		{
			$res_xml.='<orders>';
			for($i=0;$i<count($this->order_messages);$i++)
			{
				$res_xml.='
				<order>
					<tft_order_number>'.$this->order_messages[$i]["tft_order_number"].'</tft_order_number>
					<return_order_number>'.$this->order_messages[$i]["return_order_number"].'</return_order_number>
					<status_code>'.$this->order_messages[$i]["status_code"].'</status_code>
					<status_message>'.$this->order_messages[$i]["status_message"].'</status_message>
      			</order>';
			}
			$res_xml.='</orders>';
		}
		$res_xml.='
			</response>';
		return $res_xml;
	}
	public function getClientIP()
	{
		if (isset($_SERVER)) {
				if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
					return $_SERVER["HTTP_X_FORWARDED_FOR"];
				if (isset($_SERVER["HTTP_CLIENT_IP"]))
					return $_SERVER["HTTP_CLIENT_IP"];
				return $_SERVER["REMOTE_ADDR"];
			}
			if (getenv('HTTP_X_FORWARDED_FOR'))
				return getenv('HTTP_X_FORWARDED_FOR');
			if (getenv('HTTP_CLIENT_IP'))
				return getenv('HTTP_CLIENT_IP');
			return getenv('REMOTE_ADDR');
	}
	public function checkIP()
	{
		$iparray = explode(";", $this->tftorders_ip_address);
		for ($i = 0; $i < count($iparray); $i++) {
			if ($this->getClientIP() == $iparray[$i]) {
			  return true;
			}
		 }
		return false;
	}
	public function tftLog($request_data,$level,$file,$forceLog = true)
	{   
		$res=PHP_EOL.PHP_EOL.PHP_EOL;
		$res.="-----".date("Y-m-d H:i:s")."-----";
		$res.=PHP_EOL.$request_data;
		Mage::log($res,$level,$file,$forceLog);
	}
	public function ordResponseMessage($type)
	{
		$res='';
		if($type=="wrongxml")
		{
			$res="<response>
							<status_code>0</status_code>
							<status_message>Not valid XML. Please send proper xml request.</status_message>
						  </response>";	
		}
		if($type=="badrequest")
		{
			$res="<response>
						<status_code>0</status_code>
						<status_message>Bad Request, Please send proper xml request.</status_message>
					  </response>";
		}
		if($type=="nopermission")
		{
			$res="<response>
						<status_code>0</status_code>
						<status_message>You have no any permission to send request.</status_message>
					  </response>";
		}
		return $res;
	}
}