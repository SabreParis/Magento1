<?php
/**
 * created: 2015
 *
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/** @var $this Mage_Core_Model_Resource_Setup */


/**
 * Shipping Methods > Owebia Shipping - Mode de livraison 4 > Configuration
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$config = <<<JSON
{
"FR": {
"about": "UPS (France / Monaco / Belgique)",
"label": "France / Monaco / Belgique",
"shipto": "FR,MC,AD",
"conditions": "{cart.price+tax+discount} < 100",
"fees": "{table {cart.weight} in 1000:10,4000:12,9000:15,24000:20,30000:25,50000:30,60000:35,70000:40}"
},
"FR": {
"about": "FREE",
"label": "GRATUIT",
"shipto": "FR,MC,AD",
"conditions": "{cart.price+tax+discount} >= 100",
"fees": "0"
}
"IT": {
"about": "UPS (Italie)",
"label": "Italie",
"shipto": "IT",
"fees": "{table {cart.weight} in 1000:21.6,2000:24,3000:26.4,4000:30,5000:32.76,6000:38.04,7000:43.2,8000:48.54,9000:53.76,10000:58.8,12000:62.4,14000:66.12,16000:69.72,18000:73.2,20000:76.8,22000:80.28,24000:83.64,26000:87.12,28000:90.48,30000:93.6,40000:100.8,50000:108.6,60000:115.92,70000:123.24}"
},
"CH": {
"about": "UPS (Suisse)",
"label": "Suisse",
"shipto": "CH,BE",
"fees": "{table {cart.weight} in 1000:49.2,2000:57.24,3000:62.4,4000:67.44,5000:73.8,6000:76.56,7000:80.64,8000:84.72,9000:88.68,10000:92.82,12000:100.8,14000:108.96,16000:117,18000:125.1,20000:133.2,22000:138.3,24000:143.4,26000:148.56,28000:153.6,30000:158.76,40000:168,50000:177.36,60000:186.72,70000:196.08}"
}
}
JSON;

$this->setConfigData('carriers/owebiashipping4/config', $config);

