<?php
/*
 * Author Rudyuk Vitalij Anatolievich
 * Email rvansp@gmail.com
 * Blog www.cervic.info
 */
?>
<?php

class Infomodus_Upsap_Model_Ups
{

    protected $AccessLicenseNumber;
    protected $UserId;
    protected $Password;
    protected $shipperNumber;
    protected $credentials;

    public $packages;
    public $weightUnits;
    public $packageWeight;
    public $weightUnitsDescription;
    public $largePackageIndicator;

    public $includeDimensions;
    public $unitOfMeasurement;
    public $unitOfMeasurementDescription;
    public $length;
    public $width;
    public $height;

    public $customerContext;
    public $shipperName;
    public $shipperPhoneNumber;
    public $shipperAddressLine1;
    public $shipperCity;
    public $shipperStateProvinceCode;
    public $shipperPostalCode;
    public $shipperCountryCode;
    public $shipmentDescription;
    public $shipperAttentionName;

    public $shiptoCompanyName;
    public $shiptoAttentionName;
    public $shiptoPhoneNumber;
    public $shiptoAddressLine1;
    public $shiptoAddressLine2;
    public $shiptoCity;
    public $shiptoStateProvinceCode;
    public $shiptoPostalCode;
    public $shiptoCountryCode;
    public $residentialAddress;

    public $shipfromCompanyName;
    public $shipfromAttentionName;
    public $shipfromPhoneNumber;
    public $shipfromAddressLine1;
    public $shipfromCity;
    public $shipfromStateProvinceCode;
    public $shipfromPostalCode;
    public $shipfromCountryCode;

    public $serviceCode;
    public $serviceDescription;
    public $shipmentDigest;

    public $trackingNumber;
    public $shipmentIdentificationNumber;
    public $graphicImage;
    public $htmlImage;

    public $codYesNo;
    public $currencyCode;
    public $codMonetaryValue;
    public $codFundsCode;
    public $invoicelinetotal;
    public $carbon_neutral;
    public $testing;
    public $shipmentcharge = 0;
    public $qvn = 0;
    public $qvn_code = 0;
    public $qvn_email_shipper = '';
    public $qvn_email_shipto = '';
    public $adult;
    public $upsAccount = 0;
    public $accountData;
    public $saturdayDelivery;

    /* Access Point */
    public $accesspoint = 0;
    public $accesspoint_type;
    public $accesspoint_name;
    public $accesspoint_atname;
    public $accesspoint_appuid;
    public $accesspoint_street;
    public $accesspoint_street1;
    public $accesspoint_street2;
    public $accesspoint_city;
    public $accesspoint_provincecode;
    public $accesspoint_postal;
    public $accesspoint_country;
    /* Access Point */

    /*multistore*/
    public $storeId = NULL;

    /*multistore*/

    public $negotiated_rates;

    public function timeInTransit()
    {
        $cie = 'wwwcie';
        $testing = $this->testing;
        if (0 == $testing) {
            $cie = 'onlinetools';
        }
        $data = "<?xml version=\"1.0\" ?>
<AccessRequest xml:lang='en-US'>
<AccessLicenseNumber>" . $this->AccessLicenseNumber . "</AccessLicenseNumber>
<UserId>" . $this->UserID . "</UserId>
<Password>" . $this->Password . "</Password>
</AccessRequest>
<?xml version=\"1.0\" ?>
<TimeInTransitRequest xml:lang='en-US'>
<Request>
<TransactionReference>
<CustomerContext>Shipper</CustomerContext>
<XpciVersion>1.0002</XpciVersion>
</TransactionReference>
<RequestAction>TimeInTransit</RequestAction>
</Request>
<TransitFrom>
<AddressArtifactFormat>
<CountryCode>" . $this->shipfromCountryCode . "</CountryCode>
<PostcodePrimaryLow>" . $this->shipfromPostalCode . "</PostcodePrimaryLow>
</AddressArtifactFormat>
</TransitFrom>
<TransitTo>
<AddressArtifactFormat>
<PoliticalDivision2>" . $this->shiptoStateProvinceCode . "</PoliticalDivision2>
<PoliticalDivision1>" . $this->shiptoStateProvinceCode . "</PoliticalDivision1>
<CountryCode>" . $this->shiptoCountryCode . "</CountryCode>
<PostcodePrimaryLow>" . $this->shiptoPostalCode . "</PostcodePrimaryLow>
</AddressArtifactFormat>
</TransitTo>
<ShipmentWeight>
<UnitOfMeasurement>
<Code>" . $this->weightUnits . "</Code>
</UnitOfMeasurement>
<Weight>" . $this->Weight . "</Weight>
</ShipmentWeight>
<PickupDate>" . date('Ymd') . "</PickupDate>
<DocumentsOnlyIndicator />
</TimeInTransitRequest>";
        $curl = Mage::helper('upslabel/help');

        $result = $curl->curlSend('https://' . $cie . '.ups.com/ups.app/xml/TimeInTransit', $data);
        /*Mage::log($data);*/
        if (!$curl->error) {
            $xml = $this->xml2array(simplexml_load_string($result));
            if ($xml['Response']['ResponseStatusCode'] == 0 || $xml['Response']['ResponseStatusDescription'] != 'Success') {
                /*Mage::log($xml);*/
                return array('error' => 1);
            } else {
                /*Mage::log($xml);*/
                $countDay = array();
                foreach ($xml['TransitResponse']['ServiceSummary'] AS $v) {
                    $countDay[$v['Service']['Code']] = $v['EstimatedArrival']['TotalTransitDays'];
                }
                return array('error' => 0, 'days' => $countDay);
            }
        } else {
            return $result;
        }
    }

    public function xml2array($xmlObject, $out = array())
    {
        foreach ((array)$xmlObject as $index => $node) {
            $out[$index] = (is_object($node)) ? $this->xml2array($node) : (is_array($node)) ? $this->xml2array($node) : $node;
        }
        return $out;
    }

    function getShipRate()
    {
        $this->customerContext = str_replace('&', '&amp;', strtolower(Mage::app()->getStore()->getName()));
        $data = "<?xml version=\"1.0\" ?>
<AccessRequest xml:lang='en-US'>
<AccessLicenseNumber>" . $this->AccessLicenseNumber . "</AccessLicenseNumber>
<UserId>" . $this->UserID . "</UserId>
<Password>" . $this->Password . "</Password>
</AccessRequest>
<?xml version=\"1.0\"?>
<RatingServiceSelectionRequest xml:lang=\"en-US\">
  <Request>
    <TransactionReference>
      <CustomerContext>Rating and Service</CustomerContext>
      <XpciVersion>1.0</XpciVersion>
    </TransactionReference>
    <RequestAction>Rate</RequestAction>
    <RequestOption>Rate</RequestOption>
  </Request>
  <Shipment>";
        if ($this->negotiated_rates == 1) {
            $data .= "
   <RateInformation>
      <NegotiatedRatesIndicator/>
    </RateInformation>";
        }
        $data .= "<Shipper>
<Name>" . $this->shipperName . "</Name>";
        $data .= "<ShipperNumber>" . $this->shipperNumber . "</ShipperNumber>
	  <TaxIdentificationNumber></TaxIdentificationNumber>
      <Address>
    	<AddressLine1>" . $this->shipperAddressLine1 . "</AddressLine1>
    	<City>" . $this->shipperCity . "</City>
    	<StateProvinceCode>" . $this->shipperStateProvinceCode . "</StateProvinceCode>
    	<PostalCode>" . $this->shipperPostalCode . "</PostalCode>
    	<PostcodeExtendedLow></PostcodeExtendedLow>
    	<CountryCode>" . $this->shipperCountryCode . "</CountryCode>
     </Address>
    </Shipper>
	<ShipTo>
      <Address>
        <AddressLine1>" . $this->shiptoAddressLine1 . "</AddressLine1>";
        if (strlen($this->shiptoAddressLine2) > 0) {
            $data .= '<AddressLine2>' . $this->shiptoAddressLine2 . '</AddressLine2>';
        }
        $data .= "<City>" . $this->shiptoCity . "</City>
        <StateProvinceCode>" . $this->shiptoStateProvinceCode . "</StateProvinceCode>
        <PostalCode>" . $this->shiptoPostalCode . "</PostalCode>
        <CountryCode>" . $this->shiptoCountryCode . "</CountryCode>
        " . $this->residentialAddress . "
      </Address>
    </ShipTo>
    <ShipFrom>
	  <TaxIdentificationNumber></TaxIdentificationNumber>
      <Address>
        <AddressLine1>" . $this->shipfromAddressLine1 . "</AddressLine1>
        <City>" . $this->shipfromCity . "</City>
    	<StateProvinceCode>" . $this->shipfromStateProvinceCode . "</StateProvinceCode>
    	<PostalCode>" . $this->shipfromPostalCode . "</PostalCode>
    	<CountryCode>" . $this->shipfromCountryCode . "</CountryCode>
      </Address>
    </ShipFrom>";
        $data .= "<Service>
      <Code>" . $this->serviceCode . "</Code>
    </Service>";
        foreach ($this->packages AS $pv) {
            $data .= "<Package>
      <PackagingType>
        <Code>" . $pv["packagingtypecode"] . "</Code>
      </PackagingType>";
            $data .= array_key_exists('additionalhandling', $pv) ? $pv['additionalhandling'] : '';
            $data .= "<PackageWeight>
        <UnitOfMeasurement>
            <Code>" . $this->weightUnits . "</Code>";
            $packweight = array_key_exists('packweight', $pv) ? $pv['packweight'] : '';
            $weight = array_key_exists('weight', $pv) ? $pv['weight'] : '';
            $data .= "</UnitOfMeasurement>
        <Weight>" . round(($weight + (is_numeric(str_replace(',', '.', $packweight)) ? $packweight : 0)), 1) . "</Weight>" . (array_key_exists('large', $pv) ? $pv['large'] : '') . "
      </PackageWeight>
      <PackageServiceOptions>";
            if (array_key_exists('insuredmonetaryvalue', $pv) && $pv['insuredmonetaryvalue'] > 0) {
                $currencycode = array_key_exists('currencycode', $pv) ? $pv['currencycode'] : '';
                $insuredmonetaryvalue = array_key_exists('insuredmonetaryvalue', $pv) ? $pv['insuredmonetaryvalue'] : '';
                $data .= "<InsuredValue>
                <CurrencyCode>" . $currencycode . "</CurrencyCode>
                <MonetaryValue>" . $insuredmonetaryvalue . "</MonetaryValue>
                </InsuredValue>
              ";
            }
            /*$cod = array_key_exists('cod', $pv) ? $pv['cod'] : 0;
            if ($cod == 1 && ($this->shiptoCountryCode == 'US' || $this->shiptoCountryCode == 'PR' || $this->shiptoCountryCode == 'CA') && ($this->shipfromCountryCode == 'US' || $this->shipfromCountryCode == 'PR' || $this->shipfromCountryCode == 'CA')) {
                $codfundscode = array_key_exists('codfundscode', $pv) ? $pv['codfundscode'] : '';
                $codmonetaryvalue = array_key_exists('codmonetaryvalue', $pv) ? $pv['codmonetaryvalue'] : '';
                $data .= "
              <COD>
                  <CODCode>3</CODCode>
                  <CODFundsCode>" . $codfundscode . "</CODFundsCode>
                  <CODAmount>
                      <CurrencyCod>" . $currencycode . "</CurrencyCod>
                      <MonetaryValue>" . $codmonetaryvalue . "</MonetaryValue>
                  </CODAmount>
              </COD>";
            }*/
            $data .= "</PackageServiceOptions>
              </Package>";
        }
        $data .= "<ShipmentServiceOptions>";
        /*if ($this->codYesNo == 1 && $this->shiptoCountryCode != 'US' && $this->shiptoCountryCode != 'PR' && $this->shiptoCountryCode != 'CA' && $this->shipfromCountryCode != 'US' && $this->shipfromCountryCode != 'PR' && $this->shipfromCountryCode != 'CA') {
            $data .= "<COD>
                  <CODCode>3</CODCode>
                  <CODFundsCode>" . $this->codFundsCode . "</CODFundsCode>
                  <CODAmount>
                      <CurrencyCod>" . $this->currencyCode . "</CurrencyCod>
                      <MonetaryValue>" . $this->codMonetaryValue . "</MonetaryValue>
                  </CODAmount>
              </COD>";
        }*/
        if ($this->carbon_neutral == 1) {
            $data .= "<UPScarbonneutralIndicator/>";
        }
        $data .= "</ShipmentServiceOptions>";
        $data .= "</Shipment></RatingServiceSelectionRequest>";
        $cie = 'wwwcie';
        if (0 == $this->testing) {
            $cie = 'onlinetools';
        }
        $ch = curl_init('https://' . $cie . '.ups.com/ups.app/xml/Rate');
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        $result = strstr($result, '<?xml');

        //return $data;
        $xml = simplexml_load_string($result);
        if ($xml->Response->ResponseStatusCode[0] == 1) {
            $defaultPrice = $xml->RatedShipment[0]->TotalCharges[0]->MonetaryValue[0];
            return array(
                'price' => $defaultPrice,
            );
        } else {
            $error = array('error' => $xml->Response[0]->Error[0]->ErrorDescription[0]);
            return $error;
        }
    }
}

?>