<?php
/**
 * created : 10/08/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 *
 * @package Ayaline_Billboard
 */
class Ayaline_Billboard_Model_Mysql4_Billboard_Type extends Mage_Core_Model_Mysql4_Abstract
{

    protected function _construct()
    {
        $this->_init('ayalinebillboard/billboard_type', 'type_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {

        if (!Mage::helper('ayalinecore')->isValidCode($object->getIdentifier())) {
            Mage::throwException(Mage::helper('ayalinebillboard')->__('Please enter a valid identifier. For example something_1, block5, id-4.'));
        }

        return parent::_beforeSave($object);
    }

}