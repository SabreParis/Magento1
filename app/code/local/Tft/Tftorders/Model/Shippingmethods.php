<?php
class Tft_Tftorders_Model_Shippingmethods 
{
    protected $_options;
 
    public function toOptionArray()
    {
        $carriers = Mage::getSingleton('shipping/config')->getAllCarriers();
 
        $carriersActive = Mage::getSingleton('shipping/config')->getActiveCarriers();
        $carriersActive = array_keys($carriersActive);
 
        if (!$this->_options) {
            foreach ($carriers as $carrier) {
                $carrierCode = $carrier->getId();
                $carrierTitle = Mage::getStoreConfig('carriers/'.$carrierCode.'/title', Mage::app()->getStore()->getId());
                $carrierTitle = trim($carrierTitle);
 
                if (empty($carrierTitle)) {
                    continue;
                }
 
                if (in_array($carrierCode, $carriersActive)) {
                    $carrierTitle = sprintf('%s (currently active)', $carrierTitle);
                } else {
                    $carrierTitle = sprintf('%s (currently inactive)', $carrierTitle);
                }
 
                $this->_options[] = array('value'=>$carrierCode, 'label'=>$carrierTitle);
            }
        }
 
        $options = $this->_options;
 
        array_unshift($options, array('value'=>'', 'label'=> ''));
 
        return $options;
    }
}