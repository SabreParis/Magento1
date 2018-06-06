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
class Ayaline_Billboard_Model_Billboard extends Mage_Core_Model_Abstract
{

    const IS_ALLOWED_BILLBOARD = 'cms/ayalinebillboard/billboard/';

    protected $_eventPrefix = 'ayalinebillboard_billboard';
    protected $_eventObject = 'billboard';

    protected function _construct()
    {
        $this->_init('ayalinebillboard/billboard');
    }

    protected function _afterSave()
    {
        $this->_invalidateCache();

        return parent::_afterSave();
    }

    protected function _afterDelete()
    {
        $this->_invalidateCache();

        return parent::_afterDelete();
    }

    /**
     * Retrieve billboard types for this billboard
     *
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Type_Collection
     */
    public function getTypes()
    {
        return Mage::getResourceModel('ayalinebillboard/billboard_type_collection')->addBillboardFilter($this->getId());
    }

    /**
     * Validate billboard data
     *
     * @return Ayaline_Billboard_Model_Billboard
     */
    public function validate()
    {
        $displayFrom = new Zend_Date($this->getDisplayFrom(), Varien_Date::DATETIME_INTERNAL_FORMAT);
        $displayTo = new Zend_Date($this->getDisplayTo(), Varien_Date::DATETIME_INTERNAL_FORMAT);
        // The 'display to' date must be "later than" the 'display from' date
        $cmp = $displayTo->compare($displayFrom, Varien_Date::DATETIME_INTERNAL_FORMAT);    //	0 = equal, 1 = later, -1 = earlier
        if ($cmp !== 1) {
            Mage::throwException(Mage::helper('ayalinebillboard')->__('The <em>Display to</em> date must be greater than the <em>Display from</em> date'));
        }

        Mage::dispatchEvent('ayalinebillboard_validate_data', $this->_getEventData());

        return $this;
    }

    /**
     * Apply template processor filter to billboard content
     *
     * @return string
     */
    public function toHtml()
    {
        /* @var $helper Ayaline_Billboard_Helper_Data */
        $helper = Mage::helper('ayalinebillboard');

        return $helper->getBillboardTemplateProcessor()->filter($this->getContent());
    }

    /**
     * Invalidate related cache types
     *
     * @return Ayaline_Billboard_Model_Billboard
     */
    protected function _invalidateCache()
    {
        if ($types = Mage::getSingleton('ayalinebillboard/system_config')->getCacheTypes()) {
            Mage::app()->getCacheInstance()->invalidateType(array_keys($types));
        }

        return $this;
    }

}