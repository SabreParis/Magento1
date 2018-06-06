<?php

/* @var $this Mage_Core_Model_Resource_Setup */

$defaultWebsiteId = Mage::app()->getDefaultStoreView()->getWebsiteId();
$this->setConfigData("ayaline_dataflowmanager/import_product/config_website_france_id", $defaultWebsiteId);
