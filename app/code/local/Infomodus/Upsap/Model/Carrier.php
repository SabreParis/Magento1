<?php

/**
 * Created by PhpStorm.
 * User: Vitalij
 * Date: 27.02.15
 * Time: 15:26
 */
class Infomodus_Upsap_Model_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'upsap';
    private $rates = NULL;

    public function collectRates(
        Mage_Shipping_Model_Rate_Request $request
    )
    {
        $storeId = 1;
        /*multistore*/
        $storeId = Mage::app()->getStore()->getId();
        /*multistore*/
        /* @var $result Mage_Shipping_Model_Rate_Result */
        $result = Mage::getModel('shipping/rate_result');
        $session = Mage::getSingleton('checkout/session');
        $getotal = Mage::helper('checkout')->getQuote()->getGrandTotal();
        $totals = Mage::getSingleton('checkout/session')->getQuote()->getTotals();
        $orderAmount = $totals["subtotal"]->getValue();
        $model = Mage::getModel('upsap/method')->getCollection()/*multistore*/->addFieldToFilter('store_id', $storeId)/*multistore*/->addFieldToFilter('status', 1)
            ->addFieldToFilter('amount_min', array('lteq' => $orderAmount))
            ->addFieldToFilter('amount_max', array(array('gt' => $orderAmount), array('eq' => 0)));
        foreach ($model AS $method) {
            if (($request->getDestCountryId() && $request->getDestPostcode() && $request->getDestCity()) || $method->getShowifnot() == 1) {
                if ($method->getCountryIds() != '' && in_array($request->getDestCountryId(), explode(',', $method->getCountryIds()))) {
                    $result->append($this->_getStandardShippingRate($request, $method->getUpsmethodId(), $method));
                }
            }
        }

        return $result;
    }

    protected function _getStandardShippingRate(Mage_Shipping_Model_Rate_Request $request, $code2, $method)
    {
        $storeId = 1;
        /*multistore*/
        $storeId = Mage::app()->getStore()->getId();
        /*multistore*/
        $this->configMethod = Mage::getModel('upslabel/config_upsmethod');


        $rate = Mage::getModel('shipping/rate_result_method');
        $rate->setCarrier($this->_code);

        if (strlen(Mage::getStoreConfig('carriers/upsap/title', $storeId)) > 0) {
            $rate->setCarrierTitle(Mage::getStoreConfig('carriers/upsap/title', $storeId));
        }

        $rate->setMethod($method->getId() . '_' . $code2);
        $mTitle = $method->getName();

        $ratePrice = $method->getPrice();
        $rate->setMethodTitle($mTitle);
        if ($method->getDinamicPrice() == 1) {
            if ($this->rates === NULL) {
                $ups = Mage::getModel('upslabel/ups');
                $AccessLicenseNumber = Mage::getStoreConfig('upslabel/credentials/accesslicensenumber');
                $UserId = Mage::getStoreConfig('upslabel/credentials/userid');
                $Password = Mage::getStoreConfig('upslabel/credentials/password');
                $shipperNumber = Mage::getStoreConfig('upslabel/credentials/shippernumber');
                $ups->setCredentials($AccessLicenseNumber, $UserId, $Password, $shipperNumber);
                $controller = new Infomodus_Upslabel_Controller_UpslabelController();
                $prms['shipmentdescription'] = '';
                $prms['shipper_no'] = Mage::getStoreConfig('upslabel/shipping/defaultshipper');
                $prms['shiptocompanyname'] = '';
                $prms['shiptoattentionname'] = '';
                $prms['shiptophonenumber'] = '';
                $addressLine1 = $request->getDestStreet();
                $prms['shiptoaddressline1'] = is_array($addressLine1) && isset($addressLine1[0]) ? $addressLine1[0] : $addressLine1;
                $prms['shiptoaddressline2'] = is_array($addressLine1) && isset($addressLine1[1]) ? $addressLine1[1] : '';
                $prms['shiptocity'] = $request->getDestCity();
                $prms['shiptostateprovincecode'] = $request->getDestRegionCode();
                $prms['shiptopostalcode'] = $request->getDestPostcode();
                $prms['shiptocountrycode'] = $request->getDestCountryId();
                $prms['residentialaddress'] = strlen(Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getCompany()) > 0 ? '' : '<ResidentialAddress />';
                $prms['shipfrom_no'] = Mage::getStoreConfig('upslabel/shipping/defaultshipfrom');
                $prms['serviceCode'] = $code2;
                $prms['weightunits'] = Mage::getStoreConfig('upslabel/weightdimension/weightunits');
                $prms['carbon_neutral'] = Mage::getStoreConfig('upslabel/ratepayment/carbon_neutral');
                $prms['testing'] = Mage::getStoreConfig('upslabel/testmode/testing');
                $prms['qvn_email_shipper'] = '';
                $prms['qvn_email_shipto'] = '';
                $prms['adult'] = '';

                $packages = array();
                $packages[0]['weight'] = $request->getPackageWeight();
                $packages[0]['large'] = $request->getPackageWeight() > 90 ? '<LargePackageIndicator />' : '';
                $packages[0]['packagingtypecode'] = Mage::getStoreConfig('upslabel/packaging/packagingtypecode');
                $packages[0]['packagingdescription'] = Mage::getStoreConfig('upslabel/packaging/packagingdescription');
                $packages[0]['packagingreferencenumbercode'] = Mage::getStoreConfig('upslabel/packaging/packagingreferencenumbercode');
                $packages[0]['packagingreferencenumbervalue'] = Mage::getStoreConfig('upslabel/packaging/packagingreferencenumbervalue');
                $packages[0]['packagingreferencenumbervalue'] = Mage::getStoreConfig('upslabel/packaging/packagingreferencenumbervalue');
                $packages[0]['packweight'] = round(Mage::getStoreConfig('upslabel/weightdimension/packweight'), 1) > 0 ? round(Mage::getStoreConfig('upslabel/weightdimension/packweight'), 1) : '0';
                $packages[0]['additionalhandling'] = Mage::getStoreConfig('upslabel/ratepayment/additionalhandling') == 1 ? '<AdditionalHandling />' : '';
                $packages[0]['currencycode'] = Mage::getStoreConfig('upslabel/ratepayment/currencycode');
                $packages[0]['insuredmonetaryvalue'] = 0;
                $ups = $controller->setParams($ups, $prms, $packages);
                $ups->negotiated_rates = $method->getDinamicPrice() == 1 ? $method->getNegotiated() : 0;
                $ups->rates_tax = $method->getTax();

                if (Mage::getSingleton('checkout/session')->getQuote()->getSubtotal() < $method->getNegotiatedAmountFrom()) {
                    $ups->negotiated_rates = 0;
                }

                $this->rates = $ups->getShipRate();

            }
            if(isset($this->rates[$code2])){
                $ratecode2 = $this->rates[$code2];
                if (isset($ratecode2['price'])) {
                    $ratePrice = (float)$ratecode2['price'];
                    /*if(isset($ratecode2['time']) && strlen($ratecode2['time'])>0){
                        $rate->setMethodTitle($mTitle." (".($ratecode2['time']+$method->getAddday())." ".Mage::helper('adminhtml')->__('day').")");
                    }*/
                } else {
                    Mage::log($prms);
                    Mage::log($this->rates);
                }
            }
        }
        $rate->setPrice($ratePrice);
        $rate->setCost(0);
        return $rate;
    }

    public function getAllowedMethods()
    {
        $storeId = 1;
        /*multistore*/
        $storeId = Mage::app()->getStore()->getId();
        if (Mage::app()->getStore()->getCode() == 'admin') {
            $storeId = Mage::app()->getRequest()->getParam('store', 1);
            if ($storeId) {
                $code = Mage::helper('upslabel/help')->getStoreByCode($storeId);
                if ($code) {
                    $storeId = $code->getId();
                }
            }
        }
        /*multistore*/
        $arrMethods = array();
        $model = Mage::getModel('upsap/method')->getCollection()/*multistore*/->addFieldToFilter('store_id', $storeId)/*multistore*/->addFieldToFilter('status', 1);
        foreach ($model AS $method) {
            $arrMethods[$method->getId() . '_' . $method->getUpsmethodId()] = $method->getName();
        }
        return $arrMethods;
    }
}