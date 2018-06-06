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
class Ayaline_Billboard_Model_Billboard_Type extends Mage_Core_Model_Abstract
{

    const IS_ALLOWED_BILLBOARD_TYPE = 'cms/ayalinebillboard/billboard_type/';

    protected $_eventPrefix = 'ayalinebillboard_billboard_type';
    protected $_eventObject = 'billboard_type';

    protected function _construct()
    {
        $this->_init('ayalinebillboard/billboard_type');
    }

}