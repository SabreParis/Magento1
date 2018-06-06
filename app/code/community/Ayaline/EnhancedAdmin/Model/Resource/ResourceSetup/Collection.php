<?php

/**
 * created : 2014
 *
 * @category  Ayaline
 * @package   Ayaline_EnhancedAdmin
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_EnhancedAdmin_Model_Resource_ResourceSetup_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected function _construct()
    {
        parent::_construct();
        $this->_init('ayaline_enhancedadmin/resourceSetup');
    }

    /**
     * Add virtual column "hash" which is unique
     *
     * @return $this|Mage_Core_Model_Resource_Db_Collection_Abstract
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->addExpressionFieldToSelect(
            'hash',
            "MD5(CONCAT({{code}}, '_', {{type}}, '_', {{version}}))",
            array(
                'code'    => 'main_table.code',
                'type'    => 'main_table.type',
                'version' => 'main_table.version',
            )
        );

        return $this;
    }

    /**
     * Add filter on module (use setup codes)
     *
     * @param Ayaline_EnhancedAdmin_Model_Module $module
     * @return $this
     */
    public function addModuleFilter($module)
    {
        $this->addFieldToFilter('code', array('in' => $module->getAvailableSetups()->getColumnValues('code')));

        return $this;
    }

    /**
     * Add filter on applied
     *
     * @param bool $applied
     * @return $this
     */
    public function addAppliedFilter($applied = true)
    {
        $this->addFieldToFilter('applied', array('eq' => ($applied)));

        return $this;
    }

}
