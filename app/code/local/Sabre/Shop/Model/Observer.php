<?php

/**
 * created : 13/10/2015
 *
 * @category Ayaline
 * @package Ayaline_Billboard
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Shop_Model_Observer
{
    const PICTO_PATH_FOLDER = 'shops/group';

    /**
     * Ajouter l'agence de retrait au quote
     * Event: checkout_controller_onepage_save_shipping_method (frontend)
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function saveShippingAgency($observer)
    {
        /* @var $quote Mage_Sales_Model_Quote */
        $quote = $observer->getEvent()->getQuote();
        $request = $observer->getEvent()->getRequest();
        $controller = Mage::app()->getFrontController();

        $shippingAddress = $quote->getShippingAddress();

        $shippingMethod = $shippingAddress->getShippingMethod();
        $idShop = $request->getParam('shop');
        //Store withdrawal
        if ($shippingMethod === 'sabreshop_sabreshop') {

            $collection = Mage::getModel('ayalineshop/shop')->getCollection()
                              ->addFieldToFilter('used_for_shipping', 1)
                              ->addFieldToFilter('shop_id', $idShop);
            $shopObject = $collection->getFirstItem();
            if ($idShop && $shopObject->getId()) {
            $addressShopShipping = array(
                'company'    => $shopObject->getTitle(),
                'street'     => $shopObject->getStreet1() . "\n" . $shopObject->getStreet2(),
                'street1'    => $shopObject->getStreet1(),
                'street2'    => $shopObject->getStreet2(),
                'city'       => $shopObject->getCity(),
                'telephone'  => $shopObject->getTelephone(),
                'fax'        => $shopObject->getFax(),
                'region'     => null,
                'region_id'  => null,
                'postcode'   => $shopObject->getPostcode(),
                'country_id' => $shopObject->getCountryId(),
        );
            $this->doBackupShippingAddress($quote,$addressShopShipping, $shopObject);
            } else {
                $request = Mage::app()->getRequest();
                $action = $request->getActionName();
                $controller->getAction()->setFlag($action, Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            }
        } else {
            if (strpos($shippingMethod, 'owebiashipping4') !== false) {  //UPS Access Point

                $tel = $request->getParam('upsap_telephone');
                $fax = $request->getParam('upsap_fax');
                $shippingPointAddress = array(
                    'company'    => $request->getParam('upsap_name'),
                    'street'     => $request->getParam('upsap_addLine1') . "\n" . $request->getParam('upsap_addLine2'),
                    'street1'    => $request->getParam('upsap_addLine1'),
                    'street2'    => $request->getParam('upsap_addLine2'),
                    'city'       => $request->getParam('upsap_city'),
                    'telephone'  => ($tel === null || $tel === "undefined") ? "-" : $tel,
                    'fax'        => ($fax === null || $fax === "undefined") ? "-" : $fax,
                    'region'     => null,
                    'region_id'  => null,
                    'postcode'   => $request->getParam('upsap_postal'),
                    'country_id' => $request->getParam('upsap_country'),
                );
                $this->doBackupShippingAddress($quote,$shippingPointAddress);

            } elseif (strpos($shippingMethod, 'owebiashipping2') !== false) {  //UPS Shipping

                $this->doBackupShippingAddress($quote,$shippingAddress->getData(), null,true);
//
            }
        }

        return $this;
    }

    /**
     * Ajouter de oldmarker et modification de marker
     * Event: ayalineshop_shop_group_load_after (model)
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function groupAfterLoad(Varien_Event_Observer $observer)
    {
        $shopGroup = $observer->getEvent()->getShopGroup();

        if ($shopGroup->getMarker()) {
            $shopGroup->addData(array(
                'old_marker' => $shopGroup->getMarker(),
                'marker' => Mage::getBaseUrl('media') . self::PICTO_PATH_FOLDER . DS . $shopGroup->getMarker()
            ));
        }
    }

    public function doBackupShippingAddress($quote, $shippingAddress, $shop = false, $doBackup = false)
    {

        $backupAddressData =  Mage::getSingleton('checkout/session')->getData('backup_shipping_address');
        $backupAddress = $quote->getShippingAddress();
        $quote->setData('shop_id', null);

        if (!$doBackup) {
            if (!$backupAddressData) {
                Mage::getSingleton('checkout/session')->setData('backup_shipping_address',$backupAddress->getData());
            }
        } else {
            if ($backupAddressData) {
                $shippingAddress = $backupAddressData;
            }
        }
        if ($shop && $shop->getId()) {
            $quote->setData('shop_id', $shop->getId());
        }
        try {
        $quote->getShippingAddress()->addData($shippingAddress);
        } catch (exception  $e) {
            Mage::log($e->getMessage());
        }
        return $this;
    }

}