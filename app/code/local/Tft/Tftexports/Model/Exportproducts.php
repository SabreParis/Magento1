<?php
class Tft_Tftexports_Model_Exportproducts
{
	private $prefix;
	private $read;
	private $res;
	private $tftexports_ip_address;
	
	public function __construct()
	{
		$this->prefix = Mage::getConfig()->getTablePrefix();
		$this->read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$this->tftexports_ip_address = Mage::getStoreConfig('tftexports/tftexports_group/tftexports_ip_address');
	}
	
	public function getProducts()
	{
		if($this->checkIP())
		{
			$select_configurable_atttribute = $this->read->select()->from(array('a' => $this->prefix.'eav_attribute'), array('attribute_id', 'attribute_code', 'frontend_label'))
				->join(array('ao' => $this->prefix.'eav_attribute_option'), 'a.attribute_id = ao.attribute_id', array())
				->group('attribute_id');
	
			$configurable_atttribute = $this->read->fetchAll($select_configurable_atttribute); // all configurable attributes
			foreach($configurable_atttribute as $ca) {
				$attr[$ca['attribute_code']] = array('attribute_id' => $ca['attribute_id'], 'frontend_label' => $ca['frontend_label']);
			}
			
			$products_collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
		
			$xml = new DOMDocument('1.0', 'UTF-8');
			$xml_catalog = $xml->createElement('catalog');
	
			$xml_products = $xml->createElement('products');
			$xml_catalog->appendChild($xml_products);
	
			 foreach($products_collection as $product) {
				$xml_product = $xml->createElement('product');
				$item = $product->getData();
					
				$productId = $product->getId();
				$childProducts = Mage::getModel('catalog/product_type_configurable')->getChildrenIds($productId);
				$parents = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($productId);
	
				// all images
				$images = Mage::getModel('catalog/product')->load($productId)->getMediaGallery();
				
				// ba $xml_images = $xml->createElement('images');
				// ba $xml_product->appendChild($xml_images);
				$im=1;
				foreach($images['images'] as $image) {
				  $xml_image = $xml->createElement('image'.$im, Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/product'.$image['file']);
				  $xml_product->appendChild($xml_image);
				  $im++;	
				}
				
				for($i=$im;$i<6;$i++)
				{
					$xml_image = $xml->createElement('image'.$i,'');
					$xml_product->appendChild($xml_image);	
				}
				if($childProducts[0]) {
					$children = implode(',', $childProducts[0]);
					$xml_children = $xml->createElement('children', $children);
					$xml_product->appendChild($xml_children);
				}
				if($parents) {
					$xml_parent = $xml->createElement('parent', $parents[0]);
					$xml_product->appendChild($xml_parent);
						
					$xml_refrence_type = $xml->createElement('refrence_type', "Child");
					$xml_product->appendChild($xml_refrence_type);
				}
				else
				{
					$xml_refrence_type = $xml->createElement('refrence_type', "Parent");
					$xml_product->appendChild($xml_refrence_type);
				}
				
				$i = 0;
				$j = 0;
				$last_value = null;
				
				foreach($item as $key => $value) {
					
					if($key == 'thumbnail' || $key == 'small_image' || $key == 'image' || $key == 'stock_item') { // if image
					   continue;
					}
					elseif(in_array($key, array_keys($attr)) && $key != 'description') { // if configurable attribute
						   // $xml_attribute = $xml->createElement('attribute_'.$key,$product->getAttributeText($key));
							$attribute1 = $product->getResource()->getAttribute($key);
							if ($attribute1)
							{
								$att_kval = htmlspecialchars($attribute1->getFrontend()->getValue($product));
							}
							else
							{
								$att_kval='';
							}
							$xml_attribute = $xml->createElement('attribute_' . $key, $att_kval);
							$xml_product->appendChild($xml_attribute);
							$j++;
						} else {
							if(!is_array($value))
							{
								if($value!='')
								{
									$value = htmlspecialchars($value);
								}
								$value = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $value);
								$xml_{$key} = $xml->createElement($key, $value);
								$xml_product->appendChild($xml_{$key});
							}
						}
					}
					// stock_qty
					$stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
					if($stock->getQty()==''){$stockqty=0;}else{$stockqty=$stock->getQty();}
					$xml_stock_qty = $xml->createElement('stock_qty',floatval($stockqty));
					$xml_product->appendChild($xml_stock_qty);
					
					$cm=1;
					foreach($product->getCategoryIds() as $category_id) {
						$category = Mage::getModel('catalog/category')->load($category_id);
						$catnames='';
						$c=1;
						foreach ($category->getParentCategories() as $parent) {
							if($c>1){$catnames .=",";}
							$catnames .= $parent->getName();
							$c++;
						}
					
						
					  //  $xml_category = $xml->createElement('category', $category_id);
						$xml_category = $xml->createElement('category'.$cm,htmlspecialchars($catnames) );
						$xml_product->appendChild($xml_category);
						$cm++;
					}
	
					$xml_products->appendChild($xml_product);
				}
				$xml->appendChild($xml_catalog);
				$xml->formatOutput = true;
				$xml_products->appendChild($xml_product);
				$xml->appendChild($xml_catalog);
				$this->res=$xml->saveXML();
				return $this->res;
		}
		else
		{
			$this->res='<?xml version="1.0" encoding="utf-8" ?>';
			$this->res.="<response>
						<status_code>0</status_code>
						<status_message>You have no permission.</status_message>
					  </response>";
			return $this->res;
		}
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
		$iparray = explode(";", $this->tftexports_ip_address);
		for ($i = 0; $i < count($iparray); $i++) {
			if ($this->getClientIP() == $iparray[$i]) {
			  return true;
			}
		 }
		return false;
	}
}