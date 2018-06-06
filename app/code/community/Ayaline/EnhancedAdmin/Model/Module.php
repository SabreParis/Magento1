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

/**
 * Class Ayaline_EnhancedAdmin_Model_Module
 *
 * @method string getId()
 * @method Ayaline_EnhancedAdmin_Model_Module setId(string $id)
 * @method string getName()
 * @method Ayaline_EnhancedAdmin_Model_Module setName(string $name)
 * @method string getCodePool()
 * @method Ayaline_EnhancedAdmin_Model_Module setCodePool(string $codePool)
 * @method string getVersion()
 * @method Ayaline_EnhancedAdmin_Model_Module setVersion(string $version)
 * @method int getIsActive()
 * @method Ayaline_EnhancedAdmin_Model_Module setIsActive(int $isActive)
 *
 * @method Ayaline_EnhancedAdmin_Model_Resource_Module getResource()
 * @method Ayaline_EnhancedAdmin_Model_Resource_Module _getResource()
 */
class Ayaline_EnhancedAdmin_Model_Module extends Mage_Core_Model_Abstract
{

    protected $_availableSetups = null;
    protected $_currentSetups = null;
    protected static $_setup = array();

    protected function _construct()
    {
        $this->_init('ayaline_enhancedadmin/module');
    }

    /**
     * Get resource setup class
     *
     * @param $setupCode
     * @return Ayaline_EnhancedAdmin_Model_Resource_Module
     */
    protected function _getSetup($setupCode)
    {
        if (!array_key_exists($setupCode, self::$_setup)) {
            self::$_setup[$setupCode] = Mage::getResourceModel('ayaline_enhancedadmin/module', array($setupCode));
        }

        return self::$_setup[$setupCode];
    }

    /**
     * Find all setup name and versions (sql & data) for this module
     *
     * @return array
     */
    public function getCurrentSetups()
    {
        if (is_null($this->_currentSetups)) {
            $this->_currentSetups = array();

            $resources = Mage::getConfig()->getNode('global/resources')->children();

            foreach ($resources as $_key => $_config) {
                if ($_config->setup->module && (string)$_config->setup->module == $this->getId()) {
                    $this->_currentSetups[$_key] = array(
                        'db_version'   => $this->_getResource()->getSetupResource()->getDbVersion($_key),
                        'data_version' => $this->_getResource()->getSetupResource()->getDataVersion($_key),
                    );
                }
            }
        }

        return $this->_currentSetups;
    }

    /**
     * @return Varien_Data_Collection
     */
    public function getAvailableSetups()
    {
        if (is_null($this->_availableSetups)) {
            $this->_availableSetups = new Varien_Data_Collection();

            foreach ($this->getCurrentSetups() as $_setupCode => $_setupVersions) {
                $_item = new Varien_Object(
                    array(
                        'id'           => $_setupCode,
                        'code'         => $_setupCode,
                        'db_files'     => $this->_getSetup($_setupCode)->getAvailableDbVersions(),
                        'db_version'   => $_setupVersions['db_version'],
                        'data_files'   => $this->_getSetup($_setupCode)->getAvailableDataVersions(),
                        'data_version' => $_setupVersions['data_version'],
                    )
                );
                $this->_availableSetups->addItem($_item);
            }
        }

        return $this->_availableSetups;
    }

    /**
     * Apply sql updates for this module
     *
     * @return bool
     */
    public function applyAllDbModuleUpdates()
    {
        return $this->_getResource()->applyAllDbModuleUpdates($this);
    }

    /**
     * Apply data updates for this module
     *
     * @return bool
     */
    public function applyAllDataModuleUpdates()
    {
        return $this->_getResource()->applyAllDataModuleUpdates($this);
    }

    /**
     * Apply specific setup files
     *
     * @param string $setupCode
     * @param string $version
     * @param string $type
     * @return array|bool
     */
    public function applySpecificVersion($setupCode, $version, $type = Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_BOTH)
    {
        return $this->_getSetup($setupCode)->applySpecificVersion($version, $type);
    }

    /**
     * Get setup files content
     *
     * @param string $setupCode
     * @param string $version
     * @param string $type
     * @return array
     */
    public function getFilesContent($setupCode, $version, $type = Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_BOTH)
    {
        $filesContent = array(
            Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_SQL  => '',
            Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_DATA => '',
        );

        if ($type == Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_SQL) {
            $sqlFiles = $this->_getSetup($setupCode)->getAvailableDbVersions();
            if (array_key_exists($version, $sqlFiles)) {
                $filesContent[$type] = file_get_contents($sqlFiles[$version]);
            }
        } elseif ($type == Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_DATA) {
            $dataFiles = $this->_getSetup($setupCode)->getAvailableDataVersions();
            if (array_key_exists($version, $dataFiles)) {
                $filesContent[$type] = file_get_contents($dataFiles[$version]);
            }
        } else {
            $sqlFiles = $this->_getSetup($setupCode)->getAvailableDbVersions();
            if (array_key_exists($version, $sqlFiles)) {
                $filesContent[Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_SQL] = file_get_contents($sqlFiles[$version]);
            }

            $dataFiles = $this->_getSetup($setupCode)->getAvailableDataVersions();
            if (array_key_exists($version, $dataFiles)) {
                $filesContent[Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_DATA] = file_get_contents($dataFiles[$version]);
            }
        }

        return $filesContent;
    }

}
