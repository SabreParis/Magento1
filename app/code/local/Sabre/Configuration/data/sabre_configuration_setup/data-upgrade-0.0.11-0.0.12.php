<?php

/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/** @var $this Mage_Core_Model_Resource_Setup */

$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();
//Owebia Shipping mode 1
$this->setConfigData('carriers/owebiashipping1/active',0);
//Owebia Shipping mode 2
//active
$this->setConfigData('carriers/owebiashipping2/active',1);

//title

$this->setConfigData('carriers/owebiashipping2/title','UPS');

//json object
$frObject = '{
	"UPS": {
		"about": "UPS",
		"label": "National",
		"shipto": "FR,MC,AD",
		"conditions": "!{cart.free_shipping}",
		"fees": "{table {cart.weight} in 1000:10,4000:12,9000:15,24000:20,30000:25,50000:30,60000:35,70000:40}"
	},
	"FREE": {
		"about": "UPS FREE",
		"label": "National",
		"shipto": "FR,MC,AD",
		"conditions": "{cart.free_shipping}",
		"fees": 0
	},
	"IT": {
		"about": "# UPS (Italie)",
		"label": "Italie",
		"shipto": "IT",
		"fees": "{table {cart.weight} in 1000:21.6,2000:24,3000:26.4,4000:30,5000:32.76,6000:38.04,7000:43.2,8000:48.54,9000:53.76,10000:58.8,12000:62.4,14000:66.12,16000:69.72,18000:73.2,20000:76.8,22000:80.28,24000:83.64,26000:87.12,28000:90.48,30000:93.6,40000:100.8,50000:108.6,60000:115.92,70000:123.24}"
	},
	"CH": {
		"about": "UPS (Suisse)",
		"label": "Suisse (Poids: {cart.weight})",
		"shipto": "CH",
		"fees": "{table {cart.weight} in 1000:49.2,2000:57.24,3000:62.4,4000:67.44,5000:73.8,6000:76.56,7000:80.64,8000:84.72,9000:88.68,10000:92.82,12000:100.8,14000:108.96,16000:117,18000:125.1,20000:133.2,22000:138.3,24000:143.4,26000:148.56,28000:153.6,30000:158.76,40000:168,50000:177.36,60000:186.72,70000:196.08}"
	},
	"ES": {
		"about": "# UPS (Espagne)",
		"label": "Espagne (Poids: {cart.weight})",
		"shipto": "ES",
		"fees": "{table {cart.weight} in 1000:19.2,2000:20.4,3000:23.52,4000:25.92,5000:28.2,6000:33.24,7000:38.16,8000:43.08,9000:48,10000:53.04,12000:56.64,14000:60,16000:63.48,18000:66.96,20000:70.56,22000:73.68,24000:76.8,26000:79.92,28000:82.92,30000:86.64,40000:92.28,50000:98.04,60000:103.68,70000:109.32}"
	},
	"GB": {
		"about": "UPS (Grande Bretagne)",
		"label": "Grande Bretagne (Poids: {cart.weight})",
		"shipto": "GB",
		"fees": "{table {cart.weight} in 1000:19.2,2000:20.4,3000:23.52,4000:25.92,5000:25.92,6000:28.2,7000:33.24,8000:38.16,9000:43.08,10000:48,12000:53.04,14000:56.64,16000:60,18000:66.96,20000:70.56,22000:73.68,24000:76.8,26000:79.92,28000:82.92,30000:86.64,40000:92.28,50000:98.04,60000:103.68,70000:109.32}"
	},
	"GR": {
		"about": "UPS (Grèce)",
		"label": "Grèce (Poids: {cart.weight})",
		"shipto": "GR",
		"fees": "{table {cart.weight} in 1000:39.72,2000:42.48,3000:44.4,4000:46.2,5000:48,6000:56.04,7000:63.96,8000:71.88,9000:79.92,10000:87.96,12000:91.32,14000:99.48,16000:105.36,18000:111.24,20000:117,22000:122.16,24000:127.44,26000:132.6,28000:137.76,30000:142.92,40000:152.4,50000:161.88,60000:171.36,70000:180.96}"
	},
	"IE": {
		"about": "UPS (Irlande)",
		"label": "Irlande (Poids: {cart.weight})",
		"shipto": "IE",
		"fees": "{table {cart.weight} in 1000:21.84,2000:24.48,3000:27.24,4000:30,5000:32.76,6000:38.04,7000:43.2,8000:48.6,9000:53.76,10000:58.8,12000:62.64,14000:66,16000:69.6,18000:73.2,20000:76.8,22000:80.28,24000:83.64,26000:87.12,28000:90.48,30000:93.84,40000:101.28,50000:108.6,60000:115.92,70000:123.24}"
	}
}';
$enObject ='{
	"UPS": {
		"about": "UPS",
		"label": "National",
		"shipto": "FR,MC,AD",
		"conditions": "!{cart.free_shipping}",
		"fees": "{table {cart.weight} in 1000:10,4000:12,9000:15,24000:20,30000:25,50000:30,60000:35,70000:40}"
	},
	"FREE": {
		"about": "UPS FREE",
		"label": "National",
		"shipto": "FR,MC,AD",
		"conditions": "{cart.free_shipping}",
		"fees": 0
	},
	"IT": {
		"about": "# UPS (Italy)",
		"label": "Italy",
		"shipto": "IT",
		"fees": "{table {cart.weight} in 1000:21.6,2000:24,3000:26.4,4000:30,5000:32.76,6000:38.04,7000:43.2,8000:48.54,9000:53.76,10000:58.8,12000:62.4,14000:66.12,16000:69.72,18000:73.2,20000:76.8,22000:80.28,24000:83.64,26000:87.12,28000:90.48,30000:93.6,40000:100.8,50000:108.6,60000:115.92,70000:123.24}"
	},
	"CH": {
		"about": "UPS (Switzerland)",
		"label": "Switzerland (weight: {cart.weight})",
		"shipto": "CH",
		"fees": "{table {cart.weight} in 1000:49.2,2000:57.24,3000:62.4,4000:67.44,5000:73.8,6000:76.56,7000:80.64,8000:84.72,9000:88.68,10000:92.82,12000:100.8,14000:108.96,16000:117,18000:125.1,20000:133.2,22000:138.3,24000:143.4,26000:148.56,28000:153.6,30000:158.76,40000:168,50000:177.36,60000:186.72,70000:196.08}"
	},
	"ES": {
		"about": "# UPS (Spain)",
		"label": "Spain (weight: {cart.weight})",
		"shipto": "ES",
		"fees": "{table {cart.weight} in 1000:19.2,2000:20.4,3000:23.52,4000:25.92,5000:28.2,6000:33.24,7000:38.16,8000:43.08,9000:48,10000:53.04,12000:56.64,14000:60,16000:63.48,18000:66.96,20000:70.56,22000:73.68,24000:76.8,26000:79.92,28000:82.92,30000:86.64,40000:92.28,50000:98.04,60000:103.68,70000:109.32}"
	},
	"GB": {
		"about": "UPS (Great Britain)",
		"label": "Great Britain (weight: {cart.weight})",
		"shipto": "GB",
		"fees": "{table {cart.weight} in 1000:19.2,2000:20.4,3000:23.52,4000:25.92,5000:25.92,6000:28.2,7000:33.24,8000:38.16,9000:43.08,10000:48,12000:53.04,14000:56.64,16000:60,18000:66.96,20000:70.56,22000:73.68,24000:76.8,26000:79.92,28000:82.92,30000:86.64,40000:92.28,50000:98.04,60000:103.68,70000:109.32}"
	},
	"GR": {
		"about": "UPS (Greece)",
		"label": "Greece (weight: {cart.weight})",
		"shipto": "GR",
		"fees": "{table {cart.weight} in 1000:39.72,2000:42.48,3000:44.4,4000:46.2,5000:48,6000:56.04,7000:63.96,8000:71.88,9000:79.92,10000:87.96,12000:91.32,14000:99.48,16000:105.36,18000:111.24,20000:117,22000:122.16,24000:127.44,26000:132.6,28000:137.76,30000:142.92,40000:152.4,50000:161.88,60000:171.36,70000:180.96}"
	},
	"IE": {
		"about": "UPS (Ireland)",
		"label": "Ireland (weight: {cart.weight})",
		"shipto": "IE",
		"fees": "{table {cart.weight} in 1000:21.84,2000:24.48,3000:27.24,4000:30,5000:32.76,6000:38.04,7000:43.2,8000:48.6,9000:53.76,10000:58.8,12000:62.64,14000:66,16000:69.6,18000:73.2,20000:76.8,22000:80.28,24000:83.64,26000:87.12,28000:90.48,30000:93.84,40000:101.28,50000:108.6,60000:115.92,70000:123.24}"
	}
}';

//EN
$this->setConfigData('carriers/owebiashipping2/config',$enObject,'stores', $englishStoreId);
//FR
$this->setConfigData('carriers/owebiashipping2/config',$frObject,'stores', $frenchStoreId);

//URL
$this->setConfigData('carriers/owebiashipping2/tracking_view_url','');

//debug
$this->setConfigData('carriers/owebiashipping2/debug',0);

//compression
$this->setConfigData('carriers/owebiashipping2/compression',0);

//auto escaping
$this->setConfigData('carriers/owebiashipping2/auto_escaping',1);

//auto_correction
$this->setConfigData('carriers/owebiashipping2/auto_correction',1);

//stop to first_match

$this->setConfigData('carriers/owebiashipping2/stop_to_first_match',1);

//sort_order
$this->setConfigData('carriers/owebiashipping2/sort_order',2);

//Owebia Shipping mode 3
$this->setConfigData('carriers/owebiashipping3/active',0);

//Owebia Shipping mode 4
$this->setConfigData('carriers/owebiashipping4/active',0);