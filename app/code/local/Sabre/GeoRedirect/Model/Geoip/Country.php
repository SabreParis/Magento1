<?php

class Sabre_GeoRedirect_Model_Geoip_Country extends Sandfox_GeoIP_Model_Country
{

    const SYSTEM_CONFIG_COUNTRY_EMULATE = "sabre_georedirect/general/country_emulate";

    public function getCountryByIp($ip)
    {
        $countryId = Mage::getStoreConfig(self::SYSTEM_CONFIG_COUNTRY_EMULATE);
        if (!$countryId) {
            return parent::getCountryByIp($ip);
        }
        return $countryId;
    }

}
