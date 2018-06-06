<?php

/**
 * created : 7 mai 2012
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_Billboard_Model_System_Source_CustomerGroup
{

    public function toOptionArray()
    {
        $customerGroups = Mage::getResourceModel('customer/group_collection')->load()->toOptionArray();

        $found = false;
        foreach ($customerGroups as $group) {
            if ($group['value'] == 0) {
                $found = true;
            }
        }
        if (!$found) {
            array_unshift($customerGroups, array(
                    'value' => Mage_Customer_Model_Group::NOT_LOGGED_IN_ID,
                    'label' => Mage::helper('ayalinebillboard')->__('NOT LOGGED IN')
                ));
        }

        return $customerGroups;
    }

    public function toOptionHash()
    {
        $options = array();
        foreach ($this->toOptionArray() as $_option) {
            $options[$_option['value']] = $_option['label'];
        }

        return $options;
    }

}