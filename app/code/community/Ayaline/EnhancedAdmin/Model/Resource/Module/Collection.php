<?php

/**
 * created : 2013
 *
 * @category  Ayaline
 * @package   Ayaline_EnhancedAdmin
 * @author    aYaline
 * @copyright Ayaline - 2013 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_EnhancedAdmin_Model_Resource_Module_Collection extends Ayaline_Core_Model_Resource_Collection_Abstract
{

    /**
     * @return $this
     * @throws Exception
     */
    protected function _getModules()
    {
        $modules = array_keys((array)Mage::helper('ayaline_enhancedadmin/setup')->getModules()->getNode('modules')->children());
        foreach ($modules as $_moduleName) {
            $_module = Mage::getModel('ayaline_enhancedadmin/module')->load($_moduleName);
            $this->addItem($_module);
        }

        return $this;
    }

    /**
     * @param bool $printQuery
     * @param bool $logQuery
     * @return $this|Varien_Data_Collection
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        $this->_getModules();
        $this->_renderFilters()->_renderOrders()->_renderLimit();
        $this->_setIsLoaded(true);

        return $this;
    }

}
