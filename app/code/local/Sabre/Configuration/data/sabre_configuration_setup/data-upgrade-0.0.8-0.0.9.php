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

$this->setConfigData('ayaline_gua/general/enabled',1);

$this->setConfigData('ayaline_gua/general/web_property_id','');

$this->setConfigData('ayaline_gua/general/use_alternative_asynchronous_snippet',1);

$this->setConfigData('ayaline_gua/general/anonymize_ip',1);

$this->setConfigData('ayaline_gua/general/force_ssl',0);

$this->setConfigData('ayaline_gua/general/debug',0);


$this->setConfigData('ayaline_gua/create/sample_rate_customize',0);

$this->setConfigData('ayaline_gua/create/site_speed_sample_rate_customize',0);

$this->setConfigData('ayaline_gua/create/always_send_referrer_customize',0);

$this->setConfigData('ayaline_gua/create/allow_anchor_customize',0);

$this->setConfigData('ayaline_gua/create/cookie_name_customize',0);

$this->setConfigData('ayaline_gua/create/cookie_domain_customize',0);

$this->setConfigData('ayaline_gua/create/cookie_expires_customize',0);

$this->setConfigData('ayaline_gua/create/legacy_cookie_domain_customize',0);

$this->setConfigData('ayaline_gua/create/allow_linker_customize',0);

$this->setConfigData('ayaline_gua/user/user_id',1);
$this->setConfigData('ayaline_gua/ecommerce/enable_category',1);