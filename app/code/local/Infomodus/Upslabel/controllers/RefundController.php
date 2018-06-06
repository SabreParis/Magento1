<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Owner
 * Date: 10.01.12
 * Time: 13:30
 * To change this template use File | Settings | File Templates.
 */
class Infomodus_Upslabel_RefundController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function printAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate('upslabel/sales/order/refund/refund.phtml');
        $this->renderLayout();
    }

    public function customerrefundAction()
    {
        $order_id = $this->getRequest()->getParam('id');
        $this->imOrder = Mage::getModel('sales/order')->load($order_id);
        /*multistore*/
        $storeId = NULL;
        if(Mage::getConfig()->getNode('default/upslabel/myoption/multistore/active') == 1){
            $storeId = $this->imOrder->getStoreId();
        }
        /*multistore*/
        if (Mage::getStoreConfig('upslabel/return/frontend_customer_return', $storeId) == 1) {
            if ($_POST) {
                $type = 'refund';
                $collections = Mage::getModel('upslabel/upslabel');
                $collection = $collections->getCollection()->addFieldToFilter('order_id', $order_id)->addFieldToFilter('type', $type)->addFieldToFilter('status', 0)->getFirstItem();
                if ($collection->getOrderId() != $order_id) {
                    $packages = array();
                    $configOptions = new Infomodus_Upslabel_Model_Config_Options;
                    $configMethod = new Infomodus_Upslabel_Model_Config_Upsmethod;
                    $AccessLicenseNumber = Mage::getStoreConfig('upslabel/credentials/accesslicensenumber', $storeId);
                    $UserId = Mage::getStoreConfig('upslabel/credentials/userid', $storeId);
                    $Password = Mage::getStoreConfig('upslabel/credentials/password', $storeId);
                    $shipperNumber = Mage::getStoreConfig('upslabel/credentials/shippernumber', $storeId);
                    $order = Mage::getModel('sales/order')->load($order_id);
                    $shipTo = $order->getShippingAddress();

                    $path = Mage::getBaseDir('media') . DS . 'upslabel' . DS . 'label' . DS;

                    $lbl = Mage::getModel('upslabel/ups');

                    $lbl->setCredentials($AccessLicenseNumber, $UserId, $Password, $shipperNumber);
                    
                    $lbl->testing = Mage::getStoreConfig('upslabel/testmode/testing', $storeId);
                    $shipperDefault = Mage::getStoreConfig('upslabel/shipping/defaultshipper', $storeId);
                    $lbl->shipmentDescription = Infomodus_Upslabel_Helper_Help::escapeXML($shipTo->getCompany() ? $shipTo->getCompany() : $shipTo->getFirstname() . ' ' . $shipTo->getLastname());
                    $lbl->shipperName = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipperDefault.'/companyname', $storeId));
                    $lbl->shipperAttentionName = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipperDefault.'/attentionname', $storeId));
                    $lbl->shipperPhoneNumber = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipperDefault.'/phonenumber', $storeId));
                    $lbl->shipperAddressLine1 = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipperDefault.'/addressline1', $storeId));
                    $lbl->shipperCity = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipperDefault.'/city', $storeId));
                    $lbl->shipperStateProvinceCode = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipperDefault.'/stateprovincecode', $storeId));
                    $lbl->shipperPostalCode = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipperDefault.'/postalcode', $storeId));
                    $lbl->shipperCountryCode = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipperDefault.'/countrycode', $storeId));

                    $lbl->shiptoCompanyName = Infomodus_Upslabel_Helper_Help::escapeXML($shipTo->getCompany() ? $shipTo->getCompany() : $shipTo->getFirstname() . ' ' . $shipTo->getLastname());
                    $lbl->shiptoAttentionName = Infomodus_Upslabel_Helper_Help::escapeXML($shipTo->getFirstname() . ' ' . $shipTo->getLastname());
                    $lbl->shiptoPhoneNumber = Infomodus_Upslabel_Helper_Help::escapeXML($shipTo->getTelephone());
                    $lbl->shiptoAddressLine1 = Infomodus_Upslabel_Helper_Help::escapeXML(is_array($shipTo->getStreet())?trim(implode(' ', $shipTo->getStreet())):$shipTo->getStreet());
                    $lbl->shiptoCity = Infomodus_Upslabel_Helper_Help::escapeXML($shipTo->getCity());
                    $lbl->shiptoStateProvinceCode = Infomodus_Upslabel_Helper_Help::escapeXML($configOptions->getProvinceCode($shipTo->getRegion()));
                    $lbl->shiptoPostalCode = Infomodus_Upslabel_Helper_Help::escapeXML($shipTo->getPostcode());
                    $lbl->shiptoCountryCode = Infomodus_Upslabel_Helper_Help::escapeXML($shipTo->getCountryId());
                    $lbl->residentialAddress = Infomodus_Upslabel_Helper_Help::escapeXML($shipTo->getCompany() ? '' : '<ResidentialAddress />');

                    $shipfromDefault = Mage::getStoreConfig('upslabel/shipping/defaultshipfrom', $storeId);
                    $lbl->shipfromCompanyName = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipfromDefault.'/companyname', $storeId));
                    $lbl->shipfromAttentionName = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipfromDefault.'/attentionname', $storeId));
                    $lbl->shipfromPhoneNumber = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipfromDefault.'/phonenumber', $storeId));
                    $lbl->shipfromAddressLine1 = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipfromDefault.'/addressline1', $storeId));
                    $lbl->shipfromCity = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipfromDefault.'/city', $storeId));
                    $lbl->shipfromStateProvinceCode = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipfromDefault.'/stateprovincecode', $storeId));
                    $lbl->shipfromPostalCode = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipfromDefault.'/postalcode', $storeId));
                    $lbl->shipfromCountryCode = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/address_'.$shipfromDefault.'/countrycode', $storeId));

                    $lbl->serviceCode = '03';
                    $lbl->serviceDescription = $configMethod->getUpsMethodName($lbl->serviceCode);

                    /*$prod = Mage::getModel('catalog/product');*/
                    $weight = 0;
                    $paramWeight = $this->getRequest()->getParam('weight');
                    foreach ($this->getRequest()->getParam('cart') AS $k => $item) {
                        if (count($item) > 0 && $item > 0) {
                            $weight += $paramWeight[$k]*$item['qty'];
                        }
                    }
                    $packages[0]['weight'] = $weight;
                    $lbl->weightUnits = Mage::getStoreConfig('upslabel/weightdimension/weightunits', $storeId);
                    $packages[0]['large'] = $weight > 89 ? '<LargePackageIndicator />' : '';

                    $lbl->includeDimensions = 0;


                    $packages[0]['packagingtypecode'] = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/packaging/packagingtypecode', $storeId));
                    $packages[0]['packagingdescription'] = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/packaging/packagingdescription', $storeId));
                    $packages[0]['packagingreferencenumbercode'] = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/packaging/packagingreferencenumbercode', $storeId));
                    $packages[0]['packagingreferencenumbervalue'] = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/packaging/packagingreferencenumbervalue', $storeId));
                    $packages[0]['packagingreferencenumbercode2'] = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/packaging/packagingreferencenumbercode2', $storeId));
                    $packages[0]['packagingreferencenumbervalue2'] = Infomodus_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/packaging/packagingreferencenumbervalue2', $storeId));
                    $lbl->packages = $packages;

                    $lbl->codYesNo = 0;
                    $lbl->currencyCode = '';
                    $lbl->codMonetaryValue = '';
                    $upsl = $lbl->getShipFrom();
                    if (!array_key_exists('error', $upsl) || !$upsl['error']) {
                        foreach($upsl['arrResponsXML'] AS $upsl_one){
                            $upslabel = Mage::getModel('upslabel/upslabel');
                            $upslabel->setTitle('Order ' . $order_id . ' TN' . $upsl_one['trackingnumber']);
                            $upslabel->setOrderId($order_id);
                            $upslabel->setShipmentId(0);
                            $upslabel->setType($type);
                            /*$upslabel->setBase64Image();*/
                            $upslabel->setTrackingnumber($upsl_one['trackingnumber']);
                            $upslabel->setShipmentidentificationnumber($upsl['shipidnumber']);
                            $upslabel->setShipmentdigest($upsl['digest']);
                            $upslabel->setLabelname('label' . $upsl_one['trackingnumber'] . '.gif');
                            $upslabel->setCreatedTime(Date("Y-m-d H:i:s"));
                            $upslabel->setUpdateTime(Date("Y-m-d H:i:s"));
                            $upslabel->save();

                            $upslabel = Mage::getModel('upslabel/labelprice');
                            $upslabel->setOrderId($order_id);
                            $upslabel->setShipmentId(0);
                            $upslabel->setPrice($upsl['price']['price'] . " " . $upsl['price']['currency']);
                            $upslabel->save();
                        }
                        include($path . $upsl_one['trackingnumber'] . '.html');
                    }
                    else {
                        Mage::register('error', preg_replace('/\<textarea\>.*?\<\/textarea\>/is', '', $upsl['error']));
                        $this->loadLayout();
                        $this->renderLayout();
                    }
                }
                else {
                    Mage::getSingleton('core/session')->addError($this->__('For one order, you can create only one return'));
                    $this->_redirectUrl($_SERVER['HTTP_REFERER']);
                }
            }
            else {
                $this->loadLayout();
                $this->renderLayout();
            }
        }
    }

    public function customershowlabelAction()
    {
        $track_id = $this->getRequest()->getParam('id');
        $label = Mage::getModel('upslabel/upslabel')->getCollection()->addFieldToFilter('trackingnumber', $track_id)->addFieldToFilter(array(array('attribute'=>'type', 'like'=>'refund'), array('attribute'=>'type', 'like'=>'customer')));
        $label = $label->getData();
        $label = $label[0];

        $order = Mage::getModel('sales/order')->load($label['order_id']);
        /*multistore*/
        $storeId = $order->getStoreId();
        /*multistore*/
        if (Mage::getStoreConfig('upslabel/return/frontend_customer_return', $storeId) == 1) {
            if (count($label) > 0) {
                $path = Mage::getBaseDir('media') . DS . 'upslabel' . DS . 'label' . DS;
                include($path . $label['trackingnumber'] . '.html');
            }
        }
    }

}
