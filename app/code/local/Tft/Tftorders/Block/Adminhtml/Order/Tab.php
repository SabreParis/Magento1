<?php
class Tft_Tftorders_Block_Adminhtml_Order_Tab extends Mage_Adminhtml_Block_Sales_Order_Abstract
    implements Mage_Adminhtml_Block_Widget_Tab_Interface {
    
    protected function _construct() {
        $this->setTemplate('tftorders/sales/order/tab/info.phtml');
    }

    /**
     * Retrieve order model instance
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder() {
        return Mage::registry('current_order');
    }

    /**
     * Retrieve source model instance
     *
     * @return Mage_Sales_Model_Order
     */
    public function getSource() {
        return $this->getOrder();
    }


    public function getTabLabel() {
        return Mage::helper('sales')->__('TFT');
    }

    public function getTabTitle() {
        return Mage::helper('sales')->__('TFT');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }
    
    /**
     * Get fields array
     * 
     * @return Array
     */
    public function getFields() {
        $fields = array();
        $order = $this->getOrder();
        
        $fields[] = array(
            'label' => Mage::helper('tftorders')->__('TFT Order ID'),
            'value' => $order->getData('tft_order_number'),
        );
        $fields[] = array(
            'label' => Mage::helper('tftorders')->__('TFT Marketplace Source'),
            'value' => $order->getData('tft_order_marketplace_source'),
        );
        
        return $fields;
    }
    
    /**
     * Check if is a TFT  order
     * 
     * @return boolean
     */
    public function isTFTOrder() {
        return $this->getOrder()->getData('tft_from');
    }
}
