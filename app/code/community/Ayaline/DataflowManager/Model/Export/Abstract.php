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
abstract class Ayaline_DataflowManager_Model_Export_Abstract extends Ayaline_DataflowManager_Model_Abstract
{

    const EXPORT_OUTPUT_FOLDER = 'output';
    const EXPORT_PROGRESS_FOLDER = 'progress';

    protected $_emailConfiguration = 'export';

    protected $_outputFolder;
    protected $_progressFolder;
    protected $_transferFilesIo;

    /**
     * Export file name
     *
     * @var string
     */
    protected $_exportFilename;

    /**
     * Flag to delete file if is empty
     *
     * @var bool
     */
    protected $_deleteFileIfEmpty = false;

    /**
     * Flag to add or not export file to email
     *
     * @var bool
     */
    protected $_emailAttachExportFile = false;

    /**
     * Use it to access file while exporting
     *
     * @var Varien_Io_File
     */
    protected $_io;

    ####################################
    #####    Abstract functions    #####
    ####################################

    /**
     * @return string
     */
    abstract protected function _getFilename();

    abstract protected function _export();

    ##################################
    #####    Export functions    #####
    ##################################

    /**
     * Check and init export folders
     *
     * @return $this
     */
    private function __checkFolders()
    {
        $basePath = $this->_io->getCleanPath(Mage::getSingleton('ayaline_dataflowmanager/system_config')->getBasePath('export'));

        if (!$this->_io->fileExists($basePath, false)) {
            Mage::throwException("Base export folder doesn't exists => {$basePath}");
        }

        $this->_io->open(array('path' => $basePath));
        $this->_io->setAllowCreateFolders(true);

        $exportPath = trim($this->_getScriptConfig()->export_directory->__toString(), DS);

        $this->_outputFolder = $this->_io->getCleanPath($basePath . DS . $exportPath . DS . self::EXPORT_OUTPUT_FOLDER);
        if (!$this->_io->fileExists($this->_outputFolder, false)) {
            $this->_log("Export folder doesn't exists", Zend_Log::WARN);
            $this->_log("\tCreate export folder {$this->_outputFolder}", Zend_Log::WARN);
        }
        $this->_io->checkAndCreateFolder($this->_outputFolder, 0755);

        $this->_progressFolder = $this->_io->getCleanPath($basePath . DS . $exportPath . DS . self::EXPORT_PROGRESS_FOLDER);
        $this->_io->checkAndCreateFolder($this->_progressFolder, 0755);

        $this->_io->cd($this->_progressFolder);

        return $this;
    }

    /**
     * Finish processing file
     *
     * @param string $filename
     * @param bool   $forceDelete
     * @return $this
     */
    protected function _finishFile($filename, $forceDelete = false)
    {
        if ($this->_deleteFileIfEmpty || $forceDelete) {
            $this->_log("\tDelete file '{$filename}'");
            $this->_io->rm($this->_progressFolder . $filename);
        } else {
            $this->_log("\tMove '{$filename}' to output folder");
            $this->_io->mv($this->_progressFolder . $filename, $this->_outputFolder . $filename);
        }

        return $this;
    }

    /**
     * Do some actions before export
     *
     * @return $this
     */
    protected function _beforeExport()
    {
        $this->_exportFilename = $this->_getFilename();
        $this->_io->streamOpen($this->_exportFilename);

        return $this;
    }

    /**
     * Do some actions after export
     *
     * @return $this
     */
    protected function _afterExport()
    {
        $this->_transferFiles();

        $this->_finishFile($this->_exportFilename);

        if (!$this->_deleteFileIfEmpty) {
            $this->_addEmailTemplateParams('export_filename', $this->_exportFilename);

            if ($this->_emailAttachExportFile) {
                $this->_addAttachment(
                    file_get_contents($this->_outputFolder . $this->_exportFilename),
                    Zend_Mime::TYPE_OCTETSTREAM,
                    Zend_Mime::DISPOSITION_ATTACHMENT,
                    Zend_Mime::ENCODING_BASE64,
                    pathinfo($this->_exportFilename, PATHINFO_BASENAME)
                );
            }
        }

        return $this;
    }

    /**
     * @param Exception $e
     * @return $this
     */
    protected function _afterExportException($e)
    {
        return $this;
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

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _executeScript()
    {
        try {
            $this->_beforeExport();

            $this->_export();

            $this->_afterExport();
        } catch (Exception $e) {
            $this->_finishFile($this->_exportFilename, true);
            $this->_afterExportException($e);
        }
    }

    /**
     * 
     * @return mixed
     */
    protected function _transferFilesIoModel()
    {
        if (!$this->_getScriptConfig()->transport || !$this->_getScriptConfig()->transport->type) {
            Mage::throwException("The transport type is not properly configured.");
        }

        $transportType = trim((string) $this->_getScriptConfig()->transport->type);
        /* @var $dfConfig Ayaline_DataflowManager_Model_Config */
        $dfConfig = Mage::getSingleton('ayaline_dataflowmanager/config');

        $configXpath = trim(str_replace("{%type%}", $transportType, Ayaline_DataflowManager_Model_Export_Transport_Abstract::XML_PATH_CONFIG_TRANSPORT), '/');

        $configXpath .= '/model';


        $_config = $dfConfig->getScriptsConfig()->getXpath($configXpath);

        return is_array($_config) ? trim((string) array_shift($_config)) : null;
    }

    /**
     * 
     * @return Ayaline_DataflowManager_Model_Export_Transport_Abstract
     */
    protected function _transferFilesIo()
    {
        if (!$this->_transferFilesIo) {

            $modelClassName = $this->_transferFilesIoModel();

            if (!$modelClassName) {
                Mage::throwException("The transport model configuration is empty.");
            }

            $_transferFilesIo = Mage::getModel($modelClassName);

            if (!($_transferFilesIo instanceof Ayaline_DataflowManager_Model_Export_Transport_Abstract)) {
                Mage::throwException("The transport model must be an instance of Ayaline_DataflowManager_Model_Export_Transport_Abstract.");
            }

            $this->_transferFilesIo = $_transferFilesIo;
        }

        return $this->_transferFilesIo;
    }

    /**
     * 
     * @return Ayaline_DataflowManager_Model_Export_Transport_Ftp
     */
    protected function _transferFiles()
    {
        $this->_startProfiling(__METHOD__);

        try {
            if (!$this->_transferFilesEnabled()) {
                return $this;
            }

            $this->_transferFilesInit();

            $processedFiles = $this->_transferFilesList();
            $transferedFiles = array();
            foreach ($processedFiles as $_file) {
                try {
                    $this->_log("\t\t transfer file {$_file['fullpath']} :  ... ");

                    $transferResult = $this->_transferFilesIo()->write($_file['text'], $_file['fullpath']);

                    $this->_log("\t\t transfer file: " . ($transferResult ? "success" : "fail"), $transferResult ? Zend_Log::INFO : Zend_Log::WARN);
                    if ($transferResult) {
                        $transferedFiles[] = $_file;
                    }
                } catch (Exception $ex) {
                    $this->_logException($ex);
                    $this->_log($ex->getMessage(), Zend_Log::ERR);
                }
            }

            $this->_log("");

            $transferedFilesSize = count($transferedFiles);
            $processedFilesSize = count($transferedFiles);
            if ($transferedFilesSize !== $processedFilesSize) {

                $notTransferedFilesSize = $processedFilesSize - $transferedFilesSize;

                $this->_log("\t\tThere're files that have not been transfered : $notTransferedFilesSize file(s) failed", Zend_Log::WARN);
                $this->_log("");

                /**
                 * @todo Ajouter dans les parametres de mail les infos sur le transfert
                 */
            }

            $this->_log("\tTransferring processed files END");
        } catch (Exception $globalEx) {
            $this->_logException($globalEx);
            $this->_log($globalEx->getMessage(), Zend_Log::ERR);
        }
        $this->_transferFilesEnd();

        $this->_stopProfiling(__METHOD__);

        return $this;
    }

    /**
     * 
     * @return \Ayaline_DataflowManager_Model_Export_Abstract
     * @throws Mage_Core_Exception
     */
    protected function _transferFilesEnabled()
    {
        if ($this->_getScriptConfig()->transport && $this->_getScriptConfig()->transport->config && $this->_getScriptConfig()->transport->config->enabled) {
            $transportEnabledConfigPath = trim((string) $this->_getScriptConfig()->transport->config->enabled);
            $transportEnabled = Mage::getStoreConfig($transportEnabledConfigPath);
            if ($transportEnabled) {

                return true;
            }
        }

        return false;
    }

    /**
     * 
     * @return \Ayaline_DataflowManager_Model_Export_Abstract
     * @throws Mage_Core_Exception
     */
    protected function _transferFilesInit()
    {
        $this->_log("\tTransferring processed files ...");

        if (!$this->_getScriptConfig()->transport->config->remote_directory) {
            Mage::throwException("\t\tThe remote directory is not properly configured", Zend_Log::ERR);
        }

        $remoteDirConfigPath = trim((string) $this->_getScriptConfig()->transport->config->remote_directory);
        $remoteDir = Mage::getStoreConfig($remoteDirConfigPath);

        if (!$remoteDirConfigPath || !$remoteDir) {
            Mage::throwException("\t\t The remote directory is not properly configured", Zend_Log::ERR);
        }

        // Trying to open remote directory
        if (!$this->_transferFilesIo()->open($remoteDir)) {
            Mage::throwException("\t\t Error : Can't connect to remote directory $remoteDir");
        }

        return $this;
    }

    /**
     * 
     * @return \Ayaline_DataflowManager_Model_Export_Abstract
     */
    protected function _transferFilesEnd()
    {
        $this->_transferFilesIo()->close();

        return $this;
    }

    /**
     * 
     * @return array
     */
    protected function _transferFilesList()
    {
        $ioFile = new Varien_Io_File();
        $ioFile->open(array('path' => $this->_progressFolder));
        $ioFile->cd($this->_progressFolder);
        $files = $this->_io->ls(Varien_Io_File::GREP_FILES);
        foreach ($files as $fileId => $file) {
            $files[$fileId]['fullpath'] = $ioFile->getCleanPath($this->_progressFolder . DS . $file['text']);
        }
        $ioFile->close();

        return $files;
    }
}
