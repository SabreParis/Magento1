<?php

/**
 * Lengow Backend Model for Config Api key
 *
 * @category    Lengow
 * @package     Lengow_Export
 * @author      Team Connector <team-connector@lengow.com>
 * @copyright   2016 Lengow SAS
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Lengow_Export_Model_System_Config_Backend_Apikey extends Mage_Core_Model_Config_Data
{
    protected function _beforeSave()
    {
        parent::_beforeSave();
        if ((boolean)$this->getFieldsetDataValue('enabled') && $this->getValue() == '') {
            Mage::throwException(Mage::helper('lensync')->__('API Key (Token) is empty'));
        }
        if ($this->isValueChanged()) {
            /* @var $service Lengow_Export_Model_ManageOrders_Service */
            $service = Mage::getSingleton('Lengow_Export/manageorders_service');
            if ((boolean)$this->getFieldsetDataValue('enabled') && !$service->checkApiKey($this->getValue())) {
                Mage::throwException(Mage::helper('lensync')->__('API key (Token) not valid'));
            }
        }
    }
}
