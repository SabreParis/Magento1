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

// remove config first
$this->getConnection()->delete(
    $this->getTable('core/config_data'),
    ['path LIKE ?' => 'carriers/owebiashipping4%']
);


/**
 * Shipping Methods > Owebia Shipping - Mode de livraison 4 > Enabled
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/owebiashipping4/active', 1);

/**
 * Shipping Methods > Owebia Shipping - Mode de livraison 4 > Title
 *  Default: UPS AP via Owebia
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/owebiashipping4/title', 'UPS Access Point');

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
"conditions": "!{cart.free_shipping}",
"fees": "{table {cart.weight} in 1000:10,4000:12,9000:15,24000:20,30000:25,50000:30,60000:35,70000:40}"
},
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

/**
 * Shipping Methods > Owebia Shipping - Mode de livraison 4 > Tracking URL
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/owebiashipping4/tracking_view_url', 'http://wwwapps.ups.com/etracking/tracking.cgi?tracknum={tracking_number}');

/**
 * Shipping Methods > Owebia Shipping - Mode de livraison 4 > Debug
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/owebiashipping4/debug', 0);

/**
 * Shipping Methods > Owebia Shipping - Mode de livraison 4 > Compression
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/owebiashipping4/compression', 0);

/**
 * Shipping Methods > Owebia Shipping - Mode de livraison 4 > Auto-escaping
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/owebiashipping4/auto_escaping', 1);

/**
 * Shipping Methods > Owebia Shipping - Mode de livraison 4 > Auto-correction
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/owebiashipping4/auto_correction', 1);

/**
 * Shipping Methods > Owebia Shipping - Mode de livraison 4 > Stop to first match
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/owebiashipping4/stop_to_first_match', 0);

/**
 * Shipping Methods > Owebia Shipping - Mode de livraison 4 > Sort order
 *  Default: 4
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/owebiashipping4/sort_order', 1);


/**
 * Shipping Methods > UPS Access Point > Enabled
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/upsap/active', 1);

/**
 * Shipping Methods > UPS Access Point > Title
 *  Default: UPS Access Point
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/upsap/title', 'UPS Access Point');

/**
 * Shipping Methods > UPS Access Point > Type
 *  Source: upsap/config_accesspointType
 *  Default: 01
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/upsap/type', '01'); // Hold for Pickup at UPS Access Point

/**
 * Shipping Methods > UPS Access Point > Shipping Methods for Access Point
 *  Source: upsap/config_frontShippingMethod
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/upsap/shipping_method', 'owebiashipping4_FR,owebiashipping4_IT,owebiashipping4_CH');

/**
 * Shipping Methods > UPS Access Point > Enable Access Point for Countries
 *  Source: adminhtml/system_config_source_country
 *  Default: AF,ZA,AL,DZ,DE,AD,AO,AI,AQ,AG,AN,SA,AR,AM,AW,AU,AT,AZ,BS,BH,BD,BB,BE,BZ,BM,BT,BY,BO,BA,BW,BN,BR,BG,BF,BI,BJ,KH,CM,CA,CV,CL,CN,CY,CO,KM,CG,CD,KP,KR,CR,HR,CU,CI,DK,DJ,DM,SV,ES,EE,FJ,FI,FR,GA,GM,GH,GI,GD,GL,GR,GP,GU,GT,GG,GN,GQ,GW,GY,GF,GE,GS,HT,HN,HU,IN,ID,IQ,IR,IE,IS,IL,IT,JM,JP,JE,JO,KZ,KE,KG,KI,KW,RE,LA,LS,LV,LB,LY,LR,LI,LT,LU,MK,MG,MY,MW,MV,ML,MT,MA,MQ,MU,MR,YT,MX,MD,MC,MN,MS,ME,MZ,MM,NA,NR,NI,NE,NG,NU,NO,NC,NZ,NP,OM,UG,UZ,PK,PW,PA,PG,PY,NL,PH,PN,PL,PF,PR,PT,PE,QA,HK,MO,RO,GB,RU,RW,CF,DO,CZ,EH,BL,KN,SM,MF,PM,VC,SH,LC,WS,AS,ST,RS,SC,SL,SG,SK,SI,SO,SD,LK,CH,SR,SE,SJ,SZ,SY,SN,TJ,TZ,TW,TD,TF,IO,PS,TH,TL,TG,TK,TO,TT,TN,TM,TR,TV,UA,UY,VU,VE,VN,WF,YE,ZM,ZW,EG,AE,EC,ER,VA,FM,US,ET,BV,CX,NF,IM,KY,CC,CK,FO,HM,FK,MP,MH,SB,TC,VG,VI,UM,AX
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/upsap/specificcountry', 'AF,ZA,AL,DZ,DE,AD,AO,AI,AQ,AG,AN,SA,AR,AM,AW,AU,AT,AZ,BS,BH,BD,BB,BE,BZ,BM,BT,BY,BO,BA,BW,BN,BR,BG,BF,BI,BJ,KH,CM,CA,CV,CL,CN,CY,CO,KM,CG,CD,KP,KR,CR,HR,CU,CI,DK,DJ,DM,SV,ES,EE,FJ,FI,FR,GA,GM,GH,GI,GD,GL,GR,GP,GU,GT,GG,GN,GQ,GW,GY,GF,GE,GS,HT,HN,HU,IN,ID,IQ,IR,IE,IS,IL,IT,JM,JP,JE,JO,KZ,KE,KG,KI,KW,RE,LA,LS,LV,LB,LY,LR,LI,LT,LU,MK,MG,MY,MW,MV,ML,MT,MA,MQ,MU,MR,YT,MX,MD,MC,MN,MS,ME,MZ,MM,NA,NR,NI,NE,NG,NU,NO,NC,NZ,NP,OM,UG,UZ,PK,PW,PA,PG,PY,NL,PH,PN,PL,PF,PR,PT,PE,QA,HK,MO,RO,GB,RU,RW,CF,DO,CZ,EH,BL,KN,SM,MF,PM,VC,SH,LC,WS,AS,ST,RS,SC,SL,SG,SK,SI,SO,SD,LK,CH,SR,SE,SJ,SZ,SY,SN,TJ,TZ,TW,TD,TF,IO,PS,TH,TL,TG,TK,TO,TT,TN,TM,TR,TV,UA,UY,VU,VE,VN,WF,YE,ZM,ZW,EG,AE,EC,ER,VA,FM,US,ET,BV,CX,NF,IM,KY,CC,CK,FO,HM,FK,MP,MH,SB,TC,VG,VI,UM,AX');

/**
 * Shipping Methods > UPS Access Point > Sort Order
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('carriers/upsap/sort_order', 1);
