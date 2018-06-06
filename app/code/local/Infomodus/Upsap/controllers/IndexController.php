<?php
/*
 * Author Rudyuk Vitalij Anatolievich
 * Email rvansp@gmail.com
 * Blog www.cervic.info
 */
?>
<?php

class Infomodus_Upsap_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function frameAction()
    {
        header('Access-Control-Allow-Origin: http://www.ups.com');
        $url = $this->getRequest()->getParam('url');
        ?>
        <!DOCTYPE html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        </head>
        <body>
        <style type="text/css">
            body {
                margin: 0;
                padding: 0;
            }
        </style>
        <script type="text/javascript">
            window.onload = function(){
                var el = document.querySelector("iframe");
                el.style.width= window.innerWidth+'px';
                el.style.height = window.innerHeight+'px';
            }
        </script>
        <iframe src="//www.ups.com/lsw/invoke?<?php echo $url; ?>" frameborder="0" width="1080px" height="750px"
                name="dialog_upsap_access_points2"></iframe>
        </body>
        </html>
    <?php
    }

    public function accesspointcallbackAction()
    {
        header('Access-Control-Allow-Origin: http://www.ups.com');
        $url = $this->getRequest()->getParams();
        ?>
        <!DOCTYPE html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        </head>
        <body>
        <script type="text/javascript">
            window.onload = function () {
                <?php
                if($url['action'] == "cancel"):
                ?>
                window.top.closePopapMapRVA();
                <?php endif; ?>
                <?php
            if($url['action'] == "select"):
            ?>
                <?php $arrUrl= array(); foreach($url AS $k => $v): ?>
                <?php $arrUrl[$k] = $v; ?>
                <?php endforeach; ?>
                <?php /*print_r($arrUrl);*/ ?>
                window.top.setAccessPointToCheckout(<?php echo json_encode($arrUrl); ?>);
                <?php endif; ?>
            }
        </script>
        </body>
        </html>
    <?php
    }

    public function setSessionAddressAPAction()
    {
        $address = Mage::app()->getRequest()->getParams();
        $session = Mage::getSingleton('customer/session');
        if (isset($address['upsap_addLine1'])) {
            $session->setUpsapAddLine1($address['upsap_addLine1']);

            if (isset($address['upsap_addLine2'])) {
                $session->setUpsapAddLine2($address['upsap_addLine2']);
            }
            if (isset($address['upsap_addLine3'])) {
                $session->setUpsapAddLine3($address['upsap_addLine3']);
            }
            if (isset($address['upsap_city'])) {
                $session->setUpsapCity($address['upsap_city']);
            }
            if (isset($address['upsap_country'])) {
                $session->setUpsapCountry($address['upsap_country']);
            }
            if (isset($address['upsap_fax'])) {
                $session->setUpsapFax($address['upsap_fax']);
            }
            if (isset($address['upsap_state'])) {
                $session->setUpsapState($address['upsap_state']);
            }
            if (isset($address['upsap_postal'])) {
                $session->setUpsapPostal($address['upsap_postal']);
            }
            if (isset($address['upsap_appuId'])) {
                $session->setUpsapAppuId($address['upsap_appuId']);
            }
            if (isset($address['upsap_name'])) {
                $session->setUpsapName($address['upsap_name']);
            }
        }
        echo json_encode($address);
    }

    public function getSessionAddressAPAction()
    {
        $session = Mage::getSingleton('customer/session');
        $address = array();
        if ($session->getUpsapAddLine1()) {
            $address['addLine1'] = $session->getUpsapAddLine1();

            if ($session->getUpsapAddLine2()) {
                $address['addLine2'] = $session->getUpsapAddLine2();
            }
            if ($session->getUpsapAddLine3()) {
                $address['addLine3'] = $session->getUpsapAddLine3();
            }
            if ($session->getUpsapCity()) {
                $address['city'] = $session->getUpsapCity();
            }
            if ($session->getUpsapCountry()) {
                $address['country'] = $session->getUpsapCountry();
            }
            if ($session->getUpsapFax()) {
                $address['fax'] = $session->getUpsapFax();
            }
            if ($session->getUpsapState()) {
                $address['state'] = $session->getUpsapState();
            }
            if ($session->getUpsapPostal()) {
                $address['postal'] = $session->getUpsapPostal();
            }
            if ($session->getUpsapAppuId()) {
                $address['appuId'] = $session->getUpsapAppuId();
            }
            if ($session->getUpsapName()) {
                $address['name'] = $session->getUpsapName();
            }
            echo json_encode($address);
        } else {
            echo json_encode(array("error" => "empty"));
        }
    }

    public function customerAddressAction()
    {
        $addressId = Mage::app()->getRequest()->getParam('id');
        $address = Mage::getModel('customer/address')->load((int)$addressId);
        $address->explodeStreetAddress();
        if ($address->getRegionId()) {
            $region = Mage::getModel('directory/region')->load($address->getRegionId());
            $state_code = $region->getCode();
            $address->setRegion($state_code);
        }
        echo json_encode($address->getData());
    }

    public function getShippingMethodsAction()
    {
        $storeId = 1;
        /*multistore*/
        $storeId = Mage::app()->getStore()->getId();
        /*multistore*/
        if (Mage::getStoreConfig('carriers/upsap/active') == 1) {
            echo json_encode(array(
                'methods' => Mage::getStoreConfig('carriers/upsap/shipping_method', $storeId),
                'countries' => Mage::getStoreConfig('carriers/upsap/specificcountry', $storeId)
            ));
        } else {
            echo '{}';
        }
    }
}
