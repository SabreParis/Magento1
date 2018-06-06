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
class Ayaline_EnhancedAdmin_Model_Resource_Module extends Mage_Core_Model_Resource_Setup
{

    /**
     * This class is not a "real" Magento's resource, so we cheat...
     *
     * @param string $resourceName
     */
    public function __construct($resourceName)
    {
        if (is_array($resourceName) && !empty($resourceName)) {
            $resourceName = $resourceName[0];
        }
        if (!is_string($resourceName)) {
            $resourceName = self::DEFAULT_SETUP_CONNECTION;
        }
        parent::__construct($resourceName);
    }

    /**
     * @return string
     */
    public function getIdFieldName()
    {
        return 'id';
    }

    /**
     * @param string $a
     * @param string $b
     * @return int (@see version_compare)
     */
    protected function _sortVersions($a, $b)
    {
        $a = explode('-', $a);
        $a = end($a);
        $b = explode('-', $b);
        $b = end($b);

        return version_compare($a, $b);
    }

    /**
     * @param string $resourceNode
     * @return string
     */
    protected function _getSetupClassName($resourceNode)
    {
        $className = __CLASS__;
        if (isset($resourceNode->setup->class)) {
            $className = $resourceNode->setup->getClassName();
        }

        return $className;
    }

    /**
     * Public handle for Mage_Core_Model_Resource_Setup::_getResource()
     *
     * @return Mage_Core_Model_Resource_Resource
     */
    public function getSetupResource()
    {
        return $this->_getResource();
    }

    /**
     * @return array
     */
    public function getAvailableDbVersions()
    {
        $resModel = (string)$this->_connectionConfig->model;
        $modName = (string)$this->_moduleConfig[0]->getName();

        $filesDir = Mage::getModuleDir('sql', $modName) . DS . $this->_resourceName;
        if (!is_dir($filesDir) || !is_readable($filesDir)) {
            return array();
        }

        $dbFiles = array();
        $typeFiles = array();
        $regExpDb = '#^(install|upgrade)-(.*)\.(php|sql)$#i';
        $regExpType = sprintf('#^%s-(install|upgrade)-(.*)\.(php|sql)$#i', $resModel);
        $handlerDir = dir($filesDir);
        while (false !== ($file = $handlerDir->read())) {
            $matches = array();
            if (preg_match($regExpDb, $file, $matches)) {
                $dbFiles[$matches[2]] = $filesDir . DS . $file;
            } else {
                if (preg_match($regExpType, $file, $matches)) {
                    $typeFiles[$matches[2]] = $filesDir . DS . $file;
                }
            }
        }
        $handlerDir->close();

        if (empty($typeFiles) && empty($dbFiles)) {
            return array();
        }

        if (Mage::helper('ayaline_enhancedadmin')->useMysql4()) {
            foreach ($typeFiles as $version => $file) {
                $dbFiles[$version] = $file;
            }
        }

        uksort($dbFiles, array($this, '_sortVersions'));

        return $dbFiles;
    }

    /**
     * @return array
     */
    public function getAvailableDataVersions()
    {
        $modName = (string)$this->_moduleConfig[0]->getName();
        $files = array();

        $filesDir = Mage::getModuleDir('data', $modName) . DS . $this->_resourceName;
        if (is_dir($filesDir) && is_readable($filesDir)) {
            $regExp = '#^data-(install|upgrade)-(.*)\.php$#i';
            $handlerDir = dir($filesDir);
            while (false !== ($file = $handlerDir->read())) {
                $matches = array();
                if (preg_match($regExp, $file, $matches)) {
                    $files[$matches[2]] = $filesDir . DS . $file;
                }

            }
            $handlerDir->close();
        }

        if (Mage::helper('ayaline_enhancedadmin')->useMysql4()) {
            // search data files in old location
            $filesDir = Mage::getModuleDir('sql', $modName) . DS . $this->_resourceName;
            if (is_dir($filesDir) && is_readable($filesDir)) {
                $regExp = sprintf('#^%s-data-(install|upgrade)-(.*)\.php$#i', $this->_connectionConfig->model);
                $handlerDir = dir($filesDir);

                while (false !== ($file = $handlerDir->read())) {
                    $matches = array();
                    if (preg_match($regExp, $file, $matches)) {
                        $files[$matches[2]] = $filesDir . DS . $file;
                    }
                }
                $handlerDir->close();
            }
        }

        if (empty($files)) {
            return array();
        }

        uksort($files, array($this, '_sortVersions'));

        return $files;
    }

    /**
     * @param Ayaline_EnhancedAdmin_Model_Module $object
     * @param string                             $id
     * @param null                               $field
     * @return $this
     */
    public function load($object, $id, $field = null)
    {
        $module = Mage::helper('ayaline_enhancedadmin/setup')->getModules()->getNode("modules/{$id}")->children();
//        $module = Mage::getConfig()->getNode("modules/{$id}")->children();

        if ($module) {
            $object->setId($id);
            $object->setName($id);
            $object->setCodePool((string)$module->codePool);
            $object->setVersion((string)$module->version);
            $object->setIsActive(((string)$module->active == 'true') ? 1 : 0);
        }

        return $this;
    }

    /**
     * Apply specific sql/data update
     *
     * @param string $version
     * @param string $type
     * @return array|bool
     * @throws Mage_Core_Exception
     */
    public function applySpecificVersion($version, $type = Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_BOTH)
    {
        $this->hookQueries();

        $version = explode('-', $version);
        $fromVersion = reset($version);
        $toVersion = end($version);

        $installOrUpgrade = version_compare($fromVersion, $toVersion, 'eq');
        $fromVersion = $installOrUpgrade ? '' : $fromVersion;

        $actionType = false;
        if ($type == Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_SQL) {
            $actionType = $installOrUpgrade ? Mage_Core_Model_Resource_Setup::TYPE_DB_INSTALL : Mage_Core_Model_Resource_Setup::TYPE_DB_UPGRADE;
        } elseif ($type == Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_DATA) {
            $actionType = $installOrUpgrade ? Mage_Core_Model_Resource_Setup::TYPE_DATA_INSTALL : Mage_Core_Model_Resource_Setup::TYPE_DATA_UPGRADE;
        }

        switch ($actionType) {
            case self::TYPE_DB_INSTALL:
            case self::TYPE_DB_UPGRADE:
                $files = $this->getAvailableDbFiles($actionType, $fromVersion, $toVersion);
                break;
            case self::TYPE_DATA_INSTALL:
            case self::TYPE_DATA_UPGRADE:
                $files = $this->getAvailableDataFiles($actionType, $fromVersion, $toVersion);
                break;
            default:
                $dbFiles = $this->getAvailableDbFiles(($installOrUpgrade ? Mage_Core_Model_Resource_Setup::TYPE_DB_INSTALL : Mage_Core_Model_Resource_Setup::TYPE_DB_UPGRADE), $fromVersion, $toVersion);
                $dataFiles = $this->getAvailableDataFiles(($installOrUpgrade ? Mage_Core_Model_Resource_Setup::TYPE_DATA_INSTALL : Mage_Core_Model_Resource_Setup::TYPE_DATA_UPGRADE), $fromVersion, $toVersion);
                $files = array_merge($dbFiles, $dataFiles);
                break;
        }
        if (empty($files) || !$this->getConnection()) {
            return false;
        }

        $result = array('errors' => array());

        try {
            // add custom path in include_path
            Mage::helper('ayaline_enhancedadmin/setup')->updateIncludePath();

            foreach ($files as $_file) {
                $fileName = $_file['fileName'];
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
                $this->getConnection()->disallowDdlCache();
                try {
                    switch ($fileType) {
                        case 'php':
                            $_resource = Mage::getConfig()->getNode("global/resources/{$this->_resourceName}");
                            $_setupClassName = $this->_getSetupClassName($_resource);

                            $_setupClassName = Mage::helper('ayaline_enhancedadmin/setup')->createClass($_setupClassName);
                            $_setupClass = new $_setupClassName($this->_resourceName);
                            $_setupClass->includeFilename($fileName);

                            break;
                        case 'sql':
                            $sql = file_get_contents($fileName);
                            if (!empty($sql)) {
                                $this->run($sql);
                            }
                            break;
                        default:
                            break;
                    }

                    $this->_setResourceVersion($actionType, $toVersion);

                } catch (Mage_Core_Exception $e) {
                    throw Mage::exception('Mage_Core', $e->getMessage());
                } catch (Exception $e) {
                    $msg = 'Error in file: "%s" - %s';
                    foreach ($e->getTrace() as $_trace) {
                        if (isset($_trace['file']) && ($_trace['file'] == $fileName)) {
                            foreach ($_trace as $_traceKey => $_traceValue) {
                                $_traceKey = ucwords($_traceKey);
                                if (is_object($_traceValue) || is_array($_traceValue)) {
                                    $_traceValue = '<pre>' . print_r($_traceValue, true) . '</pre>';
                                }
                                $msg .= "<br />&nbsp;&nbsp;&nbsp;{$_traceKey}: {$_traceValue}";
                            }
                        }
                    }


                    throw Mage::exception('Mage_Core', sprintf($msg, $fileName, $e->getMessage()));
                }
                $version[] = $_file;
                $this->getConnection()->allowDdlCache();
            }
        } catch (Exception $e) {
            $result['errors'][] = $e->getMessage();
        }

        self::$_hadUpdates = true;
        $this->unhookQueries();

        return $result;
    }

    /**
     * Apply sql updates for a module
     *
     * @param Ayaline_EnhancedAdmin_Model_Module $module
     * @return bool
     */
    public function applyAllDbModuleUpdates($module)
    {
        Mage::app()->setUpdateMode(true);
        self::$_hadUpdates = false;

        $afterApplyUpdates = array();
        foreach ($module->getCurrentSetups() as $_resourceName => $_versions) {
            $_resource = Mage::getConfig()->getNode("global/resources/{$_resourceName}");

            if (!$_resource->setup) {
                continue;
            }

            $className = $this->_getSetupClassName($_resource);
            /** @var $setupClass Mage_Core_Model_Resource_Setup */
            $setupClass = new $className($_resourceName);
            $setupClass->applyUpdates();
            if ($setupClass->getCallAfterApplyAllUpdates()) {
                $afterApplyUpdates[] = $setupClass;
            }
        }

        /** @var $_setupClass Mage_Core_Model_Resource_Setup */
        foreach ($afterApplyUpdates as $_setupClass) {
            $_setupClass->afterApplyAllUpdates();
        }

        Mage::app()->setUpdateMode(false);
        self::$_schemaUpdatesChecked = true;

        return true;
    }

    /**
     * Apply data updates for a module
     *
     * @param Ayaline_EnhancedAdmin_Model_Module $module
     * @return bool
     */
    public function applyAllDataModuleUpdates($module)
    {
        foreach ($module->getCurrentSetups() as $_resourceName => $_versions) {
            $_resource = Mage::getConfig()->getNode("global/resources/{$_resourceName}");

            if (!$_resource->setup) {
                continue;
            }

            $className = $this->_getSetupClassName($_resource);
            /** @var $setupClass Mage_Core_Model_Resource_Setup */
            $setupClass = new $className($_resourceName);
            $setupClass->applyDataUpdates();
        }

        return true;
    }

    /**
     * Force apply data updates
     */
    public function forceApplyAllDataUpdates()
    {
        self::$_schemaUpdatesChecked = true;
        parent::applyAllDataUpdates();
    }

    ########################
    #####    Legacy    #####
    ########################

    public function getAvailableDbFiles($actionType, $fromVersion, $toVersion)
    {
        if (method_exists($this, '_getAvailableDbFiles')) {
            return $this->_getAvailableDbFiles($actionType, $fromVersion, $toVersion);
        } else {
            $resModel = (string)$this->_connectionConfig->model;
            $modName = (string)$this->_moduleConfig[0]->getName();

            $filesDir = Mage::getModuleDir('sql', $modName) . DS . $this->_resourceName;
            if (!is_dir($filesDir) || !is_readable($filesDir)) {
                return array();
            }

            $dbFiles = array();
            $typeFiles = array();
            $regExpDb = sprintf('#^%s-(.*)\.(php|sql)$#i', $actionType);
            $regExpType = sprintf('#^%s-%s-(.*)\.(php|sql)$#i', $resModel, $actionType);
            $handlerDir = dir($filesDir);
            while (false !== ($file = $handlerDir->read())) {
                $matches = array();
                if (preg_match($regExpDb, $file, $matches)) {
                    $dbFiles[$matches[1]] = $filesDir . DS . $file;
                } else {
                    if (preg_match($regExpType, $file, $matches)) {
                        $typeFiles[$matches[1]] = $filesDir . DS . $file;
                    }
                }
            }
            $handlerDir->close();

            if (empty($typeFiles) && empty($dbFiles)) {
                return array();
            }

            foreach ($typeFiles as $version => $file) {
                $dbFiles[$version] = $file;
            }

            return $this->_getModifySqlFiles($actionType, $fromVersion, $toVersion, $dbFiles);
        }
    }

    public function getAvailableDataFiles($actionType, $fromVersion, $toVersion)
    {
        if (method_exists($this, '_getAvailableDataFiles')) {
            return $this->_getAvailableDataFiles($actionType, $fromVersion, $toVersion);
        } else {
            $modName = (string)$this->_moduleConfig[0]->getName();
            $files = array();

            $filesDir = Mage::getModuleDir('data', $modName) . DS . $this->_resourceName;
            if (is_dir($filesDir) && is_readable($filesDir)) {
                $regExp = sprintf('#^%s-(.*)\.php$#i', $actionType);
                $handlerDir = dir($filesDir);
                while (false !== ($file = $handlerDir->read())) {
                    $matches = array();
                    if (preg_match($regExp, $file, $matches)) {
                        $files[$matches[1]] = $filesDir . DS . $file;
                    }

                }
                $handlerDir->close();
            }

            // search data files in old location
            $filesDir = Mage::getModuleDir('sql', $modName) . DS . $this->_resourceName;
            if (is_dir($filesDir) && is_readable($filesDir)) {
                $regExp = sprintf('#^%s-%s-(.*)\.php$#i', $this->_connectionConfig->model, $actionType);
                $handlerDir = dir($filesDir);

                while (false !== ($file = $handlerDir->read())) {
                    $matches = array();
                    if (preg_match($regExp, $file, $matches)) {
                        $files[$matches[1]] = $filesDir . DS . $file;
                    }
                }
                $handlerDir->close();
            }

            if (empty($files)) {
                return array();
            }

            return $this->_getModifySqlFiles($actionType, $fromVersion, $toVersion, $files);
        }
    }

    public function hookQueries()
    {
        /**
         * Hook queries in adapter, so that in MySQL compatibility mode extensions and custom modules will avoid
         * errors due to changes in database structure
         */
        if (method_exists(Mage::helper('core'), 'useDbCompatibleMode') && method_exists($this, '_hookQueries')) {
            if (((string)$this->_moduleConfig->codePool != 'core') && Mage::helper('core')->useDbCompatibleMode()) {
                $this->_hookQueries();
            }
        }

        return $this;
    }

    public function unhookQueries()
    {
        if (method_exists($this, '_unhookQueries')) {
            $this->_unhookQueries();
        }

        return $this;
    }

}
