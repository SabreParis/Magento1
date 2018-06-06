<?php
/**
 * created : 10/03/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Shop
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 * Schedules model
 *
 */
class Ayaline_Shop_Model_Shop_Schedules extends Mage_Core_Model_Abstract
{

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('ayalineshop/shop_schedules');
    }
}
