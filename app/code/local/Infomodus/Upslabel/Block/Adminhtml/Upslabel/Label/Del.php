<?php
/*
 * Author Rudyuk Vitalij Anatolievich
 * Email rvansp@gmail.com
 * Blog www.cervic.info
 */
?>
<?php

class Infomodus_Upslabel_Block_Adminhtml_Upslabel_Label_Del extends Mage_Adminhtml_Block_Widget_Tabs
{

    protected function _beforeToHtml()
    {
        $order_id = $this->getRequest()->getParam('order_id');
        $shipment_id = $this->getRequest()->getParam('shipment_id');
        $type = $this->getRequest()->getParam('type');

        /*multistore*/
        $storeId = NULL;
        if(Mage::getConfig()->getNode('default/upslabel/myoption/multistore/active') == 1){
            $order= Mage::getModel('sales/order')->load($order_id);
            $storeId = $order->getStoreId();
        }
        /*multistore*/

        $AccessLicenseNumber = Mage::getStoreConfig('upslabel/credentials/accesslicensenumber', $storeId);
        $UserId = Mage::getStoreConfig('upslabel/credentials/userid', $storeId);
        $Password = Mage::getStoreConfig('upslabel/credentials/password', $storeId);
        $shipperNumber = Mage::getStoreConfig('upslabel/credentials/shippernumber', $storeId);

        $collection = Mage::getModel('upslabel/upslabel');
        $colls = $collection->getCollection()->addFieldToFilter('shipment_id', $shipment_id)->addFieldToFilter('type', $type);
        if (count($colls) > 0) {
            foreach ($colls AS $v) {
                $coll = $v;

                $lbl = Mage::getModel('upslabel/ups');

                $lbl->setCredentials($AccessLicenseNumber, $UserId, $Password, $shipperNumber);
                $lbl->packagingReferenceNumberCode = Mage::getStoreConfig('upslabel/packaging/packagingreferencenumbercode', $storeId);
                $lbl->testing = Mage::getStoreConfig('upslabel/testmode/testing', $storeId);
                /*multistore*/
                $lbl->storeId = $storeId;
                /*multistore*/

                $result = $lbl->deleteLabel($coll['shipmentidentificationnumber']);
                if (!is_array($result)) {
                    @unlink(Mage::getBaseDir('media') . '/upslabel/label/' . $coll['labelname']);
                    if(file_exists(Mage::getBaseDir('media') . '/upslabel/label/' . $coll['trackingnumber'] . '.html')){
                        @unlink(Mage::getBaseDir('media') . '/upslabel/label/' . $coll['trackingnumber'] . '.html');
                    }
                    if(file_exists(Mage::getBaseDir('media') . '/upslabel/label/' . "HVR" . $coll['shipmentidentificationnumber'] . ".html")){
                        @unlink(Mage::getBaseDir('media') . '/upslabel/label/' . "HVR" . $coll['shipmentidentificationnumber'] . ".html");
                    }
                    if(file_exists(Mage::getBaseDir('media') . '/upslabel/inter_pdf/' . $coll['trackingnumber'] . ".pdf")){
                        @unlink(Mage::getBaseDir('media') . '/upslabel/inter_pdf/' . $coll['trackingnumber'] . ".pdf");
                    }
                    $collection->setId($coll->getId())->delete();
                    $shipm = Mage::getModel('sales/order_shipment')->load($shipment_id);
                    $tracks = $shipm->getAllTracks();
                    foreach ($tracks as $track) {
                        if ($track->getNumber() == $coll['trackingnumber']) {
                            $track->delete();
                        }
                    }
                } else {
                    echo 'Error';
                    print_r($result);
                }
            }
        }
        echo '<br />Removal was successful. Back to <a href="' . $this->getUrl('adminhtml/sales_order/view/order_id/' . $order_id) . '">order</a>.';
        if ($type == 'shipment') {
            echo ' Back to <a href="' . $this->getUrl('adminhtml/sales_order_shipment/view/shipment_id/' . $shipment_id) . '">shipment</a>.';
        } else {
            echo ' Back to <a href="' . $this->getUrl('adminhtml/sales_order_creditmemo/view/creditmemo_id/' . $shipment_id) . '">credit memo</a>.';
        }
        echo ' Or <a href="' . $this->getUrl('adminhtml/upslabel_upslabel/intermediate/order_id/' . $order_id . '/shipment_id/' . $shipment_id . '/type/'.$type) . '"> Create new</a>';


        return parent::_beforeToHtml();
    }

}