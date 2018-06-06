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
class Sabre_Configuration_Model_Admin_Variable extends Mage_Admin_Model_Variable
{

    protected $_variables = [];

    /**
     * {@inheritdoc}
     */
    public function isPathAllowed($path)
    {
        if (!array_key_exists($path, $this->_variables)) {
            $this->_variables[$path] = Mage::getResourceSingleton('sabre_configuration/admin_variable')->isPathAllowed($path);
        }

        return $this->_variables[$path];
    }

}
