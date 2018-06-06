<?php

/**
 * created : 2014
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
abstract class Ayaline_DataflowManager_Model_Import_Abstract extends Ayaline_DataflowManager_Model_Abstract
{

    const IMPORT_INPUT_FOLDER = 'input';
    const IMPORT_PROCESSED_FOLDER = 'processed';
    const IMPORT_ERROR_FOLDER = 'error';
    const IMPORT_PROCESSING_FOLDER = 'processing';

    const IMPORT_CACHE_EXISTS_EXCEPTION_CODE = 93553;

    protected $_emailConfiguration = 'import';

    protected $_inputFolder;
    protected $_processedFolder;
    protected $_processingFolder;
    protected $_errorFolder;

    /**
     * Flag to archive file after import
     *
     * @var bool
     */
    protected $_canArchive = true;

    /**
     * @var array
     */
    protected $_filesSuccess = array();

    /**
     * @var array
     */
    protected $_filesError = array();

    /**
     * Use it to access file while importing
     *
     * @var Varien_Io_File
     */
    protected $_io;

    /**
     * @var Ayaline_DataflowManager_Model_Resource_DataflowManager_Import_Cache
     */
    protected $_importCacheResource;

    /**
     * Flag to use cache on imported data
     *
     * @var bool
     */
    protected $_useCache = true;

    ####################################
    #####    Abstract functions    #####
    ####################################

    /**
     * Import action
     *
     * @param string $filename
     * @return $this
     */
    abstract protected function _import($filename);

    ##################################
    #####    Import functions    #####
    ##################################

    /**
     * Check and init import folders
     *  check if input folder exists
     *  init output & error folders (create if not exists)
     *
     * @return $this
     */
    private function __checkFolders()
    {
        $basePath = $this->_io->getCleanPath(Mage::getSingleton('ayaline_dataflowmanager/system_config')->getBasePath('import'));

        if (!$this->_io->fileExists($basePath, false)) {
            Mage::throwException("Base import folder doesn't exists => {$basePath}");
        }

        $this->_io->open(array('path' => $basePath));

        $importPath = trim($this->_getScriptConfig()->import_directory->__toString(), DS);

        $this->_inputFolder = $this->_io->getCleanPath($basePath . DS . $importPath . DS . self::IMPORT_INPUT_FOLDER);

        if (!$this->_io->fileExists($this->_inputFolder, false)) {
            Mage::throwException("Import directory doesn't exists => {$this->_inputFolder}");
        }

        $this->_io->setAllowCreateFolders(true);
        $this->_processingFolder = $this->_io->getCleanPath($basePath . DS . $importPath . DS . self::IMPORT_PROCESSING_FOLDER);
        $this->_io->checkAndCreateFolder($this->_processingFolder, 0755);
        $this->_processedFolder = $this->_io->getCleanPath($basePath . DS . $importPath . DS . self::IMPORT_PROCESSED_FOLDER);
        $this->_io->checkAndCreateFolder($this->_processedFolder, 0755);
        $this->_errorFolder = $this->_io->getCleanPath($basePath . DS . $importPath . DS . self::IMPORT_ERROR_FOLDER);
        $this->_io->checkAndCreateFolder($this->_errorFolder, 0755);

        return $this;
    }

    /**
     * Archive file
     *  set $error to true to archive file into error folder
     *
     * @param string $filename
     * @param bool   $error
     * @return $this
     */
    private function __archive($filename, $error = false)
    {
        if ($this->_canArchive) {
            $this->_log("\tArchive {$filename} into " . ($error ? $this->_errorFolder : $this->_processedFolder));
            $this->_io->mv($filename, ($error ? $this->_errorFolder : $this->_processedFolder) . basename($filename));
        } else {
            $this->_log("\tRestore {$filename} into {$this->_inputFolder}");
            $this->_io->mv($filename, $this->_inputFolder . basename($filename));
        }

        return $this;
    }

    /**
     * Move file from input to processing directory
     *
     * @param string $filename
     * @return string
     */
    private function __processing($filename)
    {
        $this->_log(" Move file {$filename} to processing folder");

        $processingFilename = $this->_processingFolder . basename($filename);
        $mvResult = $this->_io->mv($filename, $processingFilename);

        return (!$mvResult) ? false : $processingFilename;
    }

    /**
     * Retrieve files to import
     *
     * @return array
     */
    private function __getFilesName()
    {
        if ($customFile = $this->_getArgument('file', false)) {
            $this->_log(" Process import with filename specified: {$customFile}");
            $customFileName = $this->_inputFolder . $customFile;
            if (!$this->_io->fileExists($customFileName)) {
                Mage::throwException("No file to import => {$customFileName}");
            }

            $filesName = array($customFileName);
        } else {
            $filesName = glob($this->_inputFolder . $this->_getFilesNamePattern());
            $filesName = is_array($filesName) ? $filesName : array();

            if (!count($filesName)) {
                Mage::throwException("No files to import => {$this->_inputFolder}{$this->_getFilesNamePattern()}");
            }

            if ((bool)$this->_getArgument('process_one_file', false)) {
                $filesName = array($filesName[0]); // get first file
            }
        }

        return $filesName;
    }

    /**
     * File name pattern
     *
     * @return string
     */
    protected function _getFilesNamePattern()
    {
        return $this->_getScriptConfig()->files_name_pattern->__toString();
    }

    /**
     * Do some actions before import
     *
     * @param string $filename
     * @return $this
     */
    protected function _beforeImport($filename)
    {
        return $this;
    }

    /**
     * Do some actions after import
     *
     * @param string $filename
     * @return $this
     */
    protected function _afterImport($filename)
    {
        return $this;
    }

    /**
     * @param Exception $e
     * @param string    $filename
     * @return $this
     */
    protected function _afterImportException($e, $filename)
    {
        return $this;
    }

    /**
     * A place to release memory between each files
     *
     * @return $this
     */
    protected function _clearData()
    {
        return $this;
    }

    #################################
    #####    Cache functions    #####
    #################################

    /**
     * @param string|array $data
     * @return string
     * @throws Mage_Core_Exception
     */
    protected function _generateCacheHash($data)
    {
        if (is_string($data)) {
            return md5($data);
        }
        if (is_array($data)) {
            return md5(serialize($data));
        }

        Mage::throwException("Can't create cache hash, unsupported type: " . gettype($data));
    }

    /**
     * @param int    $objectId
     * @param string $cacheHash from self::_getCacheHash
     * @return bool
     */
    protected function _cacheExists($objectId, $cacheHash)
    {
        return $this->_importCacheResource->checkObjectHashExists($objectId, $this->_scriptCode, $cacheHash);
    }

    /**
     * @param int          $objectId
     * @param string|array $data
     * @return string|bool return true in case of import cache is disabled
     * @throws Mage_Core_Exception
     */
    protected function _canProcessObject($objectId, $data)
    {
        if ($this->_useCache) {
            $cacheHash = $this->_generateCacheHash($data);
            if ($this->_cacheExists($objectId, $cacheHash)) {
                throw Mage::exception('Mage_Core', "No update update required for {$objectId} (type: {$this->_scriptCode} ; hash: {$cacheHash})", self::IMPORT_CACHE_EXISTS_EXCEPTION_CODE);
            }

            return $cacheHash;
        }

        return true;
    }

    /**
     * @param int    $objectId
     * @param string $cacheHash from self::_getCacheHash
     * @return $this
     */
    protected function _updateCacheHash($objectId, $cacheHash)
    {
        if ($this->_useCache) {
            try {
                $this->_importCacheResource->updateCache($objectId, $this->_scriptCode, $cacheHash);
            } catch (Exception $e) {
                $this->_log("An error occurred while saving object cache: {$e->getMessage()}", Zend_Log::ERR);
                $this->_logException($e);
            }
        }

        return $this;
    }

    #########################################
    #####    Documentation functions    #####
    #########################################

    protected function _getCommonDocumentation()
    {
        $doc = <<<DOC
Import arguments:
--use_cache         Disable/enable cache on imported data (default: enable)
--file              Specify a file to import
--process_one_file  Only process one file - the first one (default: no - 1: yes)
DOC;

        $doc = "{$doc}\n\n" . parent::_getCommonDocumentation();

        return $doc;
    }

    ##########################################
    #####    Execute script functions    #####
    ##########################################

    /**
     * {@inheritdoc}
     */
    protected function _beforeExecuteScript()
    {
        parent::_beforeExecuteScript();

        $this->_io = new Varien_Io_File();
        $this->__checkFolders();

        $this->_importCacheResource = Mage::getResourceModel('ayaline_dataflowmanager/dataflowManager_import_cache');

        $this->_useCache = (bool)$this->_getArgument('use_cache', true);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterExecuteScript()
    {
        parent::_afterExecuteScript();

        $this->_addEmailTemplateParams('files_success', "<ul><li>" . implode('</li><li>', $this->_filesSuccess) . "</li></ul>");
        $this->_addEmailTemplateParams('files_success_count', count($this->_filesSuccess));
        $this->_addEmailTemplateParams('files_error', "<ul><li>" . implode('</li><li>', $this->_filesError) . "</li></ul>");
        $this->_addEmailTemplateParams('files_error_count', count($this->_filesError));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _executeScript()
    {
        foreach ($this->__getFilesName() as $_filename) {

            $_basename = basename($_filename);
            $this->_startProfiling("import_{$_basename}");

            try {
                $this->_log("Process file {$_basename}");

                if ($_filename = $this->__processing($_filename)) {
                    $this->_beforeImport($_filename);

                    $this->_import($_filename);

                    $this->_afterImport($_filename);

                    $this->__archive($_filename, false);

                    $this->_filesSuccess[] = $_filename;
                } else {
                    $this->_log("Can't move file into processing folder - check if file has not been processed by an other thread", Zend_Log::WARN);
                }
            } catch (Exception $e) {
                $this->__archive($_filename, true);

                $this->_log($e->getMessage(), Zend_Log::ERR);
                $this->_logException($e);

                $this->_filesError[] = $_filename;

                $this->_afterImportException($e, $_filename);
            }

            $this->_clearData();

            $this->_stopProfiling("import_{$_basename}");
        }
    }

}
