<?php

/**
 * created : 08/10/2015
 *
 * @category Ayaline
 * @package Ayaline_Billboard
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Shop_Model_Carrier_Sabreshop extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{

    protected $_code = 'sabreshop';
    protected $_isFixed = true;

    /**
     * Enter description here...
     *
     * @param Mage_Shipping_Model_Rate_Request $data
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $freeBoxes = 0;
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {

                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $freeBoxes += $item->getQty() * $child->getQty();
                        }
                    }
                } elseif ($item->getFreeShipping()) {
                    $freeBoxes += $item->getQty();
                }
            }
        }
        $this->setFreeBoxes($freeBoxes);

        $result = Mage::getModel('shipping/rate_result');

        $method = Mage::getModel('shipping/rate_result_method');

        $method->setCarrier('sabreshop');
        $method->setCarrierTitle($this->getConfigData('title'));
        $method->setMethod('sabreshop');
        $method->setMethodTitle($this->getConfigData('name'));

        $shippingPrice = $this->getConfigData('price');
        $shippingPrice = $this->getFinalPriceWithHandlingFee($shippingPrice);

        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);

        $result->append($method);


        return $result;
    }

    public function getAllowedMethods()
    {
        return array('sabreshop' => $this->getConfigData('name'));
    }

}
