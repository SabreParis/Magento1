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
class Ayaline_EnhancedAdmin_Model_Resource_ResourceSetup extends Mage_Core_Model_Resource_Db_Abstract
{

    protected $_doCheckSetups = true;

    protected function _construct()
    {
        $this->_init('ayaline_enhancedadmin/resource', 'hash');
        $this->_isPkAutoIncrement = false;
    }

    /**
     * Check setups for all modules
     *
     * @return $this
     */
    public function checkSetups()
    {
        /** @var $_module Ayaline_EnhancedAdmin_Model_Module */
        foreach (Mage::getResourceModel('ayaline_enhancedadmin/module_collection') as $_module) {
            $this->checkSetupsByModule($_module);
        }

        return $this;
    }

    /**
     * Check if setup's module are applied or not and save into table
     *
     * @param Ayaline_EnhancedAdmin_Model_Module $module
     * @return $this
     */
    public function checkSetupsByModule($module)
    {
        /** @var $_setup Varien_Object */
        foreach ($module->getAvailableSetups() as $_setup) {

            // sql
            foreach ($_setup->getData('db_files') as $_dbVersion => $_dbFile) {

                $_toVersion = explode('-', $_dbVersion);
                $_toVersion = end($_toVersion);

                // add test for guess if setups are already applied (for example install module on existing Magento)
                $_dbApplied = version_compare($module->getVersion(), $_toVersion, 'ge');

                $this->_getWriteAdapter()->insertOnDuplicate(
                    $this->getMainTable(),
                    array(
                        'code'       => $_setup->getData('code'),
                        'type'       => Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_SQL,
                        'version'    => $_dbVersion,
                        'file'       => $_dbFile,
                        'applied'    => (int)$_dbApplied,
                        'applied_at' => $_dbApplied ? Mage::helper('ayaline_enhancedadmin')->getInstallDate() : null,
                    ),
                    array('file')
                );

            }

            // data
            foreach ($_setup->getData('data_files') as $_dataVersion => $_dataFile) {

                $_toVersion = explode('-', $_dataVersion);
                $_toVersion = end($_toVersion);

                // add test for guess if setups are already applied (for example install module on existing Magento)
                $_dataApplied = version_compare($module->getVersion(), $_toVersion, 'ge');

                $this->_getWriteAdapter()->insertOnDuplicate(
                    $this->getMainTable(),
                    array(
                        'code'       => $_setup->getData('code'),
                        'type'       => Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_DATA,
                        'version'    => $_dataVersion,
                        'file'       => $_dataFile,
                        'applied'    => (int)$_dataApplied,
                        'applied_at' => $_dataApplied ? Mage::helper('ayaline_enhancedadmin')->getInstallDate() : null,
                    ),
                    array('file')
                );

            }
        }

        return $this;
    }

    /**
     * Apply setups which are not applied for all modules
     *
     * @return $this
     */
    public function applyUnAppliedSetups()
    {
        /** @var $_module Ayaline_EnhancedAdmin_Model_Module */
        foreach (Mage::getResourceModel('ayaline_enhancedadmin/module_collection') as $_module) {
            $this->applyUnAppliedSetupsByModule($_module);
        }

        return $this;
    }

    /**
     * Apply setups which are not applied for a module
     *
     * @param Ayaline_EnhancedAdmin_Model_Module $module
     * @return array
     */
    public function applyUnAppliedSetupsByModule($module)
    {
        $collection = Mage::getResourceModel('ayaline_enhancedadmin/resourceSetup_collection');
        $collection->addModuleFilter($module);
        $collection->addAppliedFilter(false);
        $collection->addOrder('code', Varien_Data_Collection::SORT_ORDER_ASC);
        $collection->addOrder('version', Varien_Data_Collection::SORT_ORDER_ASC);

        foreach ($collection as $_resourceSetup) {

            if ($_result = $module->applySpecificVersion($_resourceSetup->getCode(), $_resourceSetup->getVersion(), $_resourceSetup->getType())) {
                if (count($_result['errors'])) {
                    foreach ($_result['errors'] as $_error) {
                        Mage::getSingleton('adminhtml/session')->addError($_error);
                    }
                } else {
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ayaline_enhancedadmin')->__('Setup %s for resource "%s", applied.', $_resourceSetup->getVersion(), $_resourceSetup->getCode()));
                }
            } else {
                Mage::throwException(Mage::helper('ayaline_enhancedadmin')->__('No setup files or no database connection.'));
            }
        }

        return $this;
    }

    /**
     * Mark setup as applied
     *
     * @param string $resName
     * @param string $version
     * @param string $type
     * @return $this
     */
    public function setIsApplied($resName, $version, $type)
    {
        // on first install, it may be possible that table doesn't exists yet
        if ($this->getReadConnection()->isTableExists($this->getMainTable())) {
            if ($this->_doCheckSetups) {
                $this->checkSetups();
                $this->_doCheckSetups = false;
            }

            $this->_getWriteAdapter()->update(
                $this->getMainTable(),
                array(
                    'applied'    => 1,
                    'applied_at' => now(),
                ),
                array(
                    'code = ?' => $resName,
                    'type = ?' => $type,
                    "({$this->_getWriteAdapter()->quoteInto('version = ?', $version)} OR {$this->_getWriteAdapter()->quoteInto('version LIKE ?', '%-' . $version)})",
                )
            );
        }

        return $this;
    }

}
