<?php

/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Configuration_Model_Admin_Block extends Mage_Admin_Model_Block
{

    protected $_blocks = [];

    public function isTypeAllowed($type)
    {
        if (!array_key_exists($type, $this->_blocks)) {
            $this->_blocks[$type] = Mage::getResourceSingleton('sabre_configuration/admin_block')->isTypeAllowed($type);
        }

        return $this->_blocks[$type];
    }

}
