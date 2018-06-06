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
class Ayaline_DataflowManager_Model_Utils_Log_Archive extends Ayaline_DataflowManager_Model_Abstract
{

    protected $_sendEmail = false;

    protected $_date;

    /**
     * {@inheritdoc}
     */
    protected function _getDocumentation()
    {
        $doc = <<<DOC
Archive log files for all data flows

No arguments.
DOC;

        return $doc;
    }

    /**
     * Check if script can be executed
     *
     * @return bool
     */
    protected function _validate()
    {
        return true;
    }

    /**
     * Script entry point
     *
     * @return $this
     */
    protected function _executeScript()
    {
        $this->_date = new DateTime();
        $this->_date->sub(new DateInterval('P1M'));
        $this->_date = $this->_date->format('Y-m');

        // foreach scripts get archive file
        $this->_process(Mage::getSingleton('ayaline_dataflowmanager/config')->getScriptsConfig()->getNode());
    }

    /**
     * @param string                   $dataflowCode
     * @param Varien_Simplexml_Element $dataflowConfig
     * @return bool|string
     */
    protected function _getLogPath($dataflowCode, $dataflowConfig)
    {
        $logPath = Mage::getSingleton('ayaline_dataflowmanager/system_config')->getLogPath();
        if ($logPath) {
            if (Mage::getSingleton('ayaline_dataflowmanager/system_config')->useRelativePath()) {
                $logPath = Mage::getBaseDir() . $logPath;
            }
        } else { // no config log path
            return false;
        }

        $logPath .= "/{$dataflowCode}";

        if (!is_dir($logPath)) {
            return false;
        }

        return $logPath;
    }

    /**
     * @param Varien_Simplexml_Element $node
     * @param null                     $parentCode
     * @return $this
     */
    protected function _process($node, $parentCode = null)
    {
        /** @var $_dataflowConfig Varien_Simplexml_Element */
        foreach ($node as $_dataflowCode => $_dataflowConfig) {
            $_dataflowCode = ($parentCode === null) ? $_dataflowCode : "{$parentCode}/{$_dataflowCode}";

            $this->_startProfiling($_dataflowCode);

            if (!($_dataflowConfig->name || $_dataflowConfig->model)) { // name & model are mandatory
                $this->_process($_dataflowConfig, $_dataflowCode);
            } else {
                try {
                    $this->_log("Process dataflow {$_dataflowCode}");

                    $_logPath = $this->_getLogPath($_dataflowCode, $_dataflowConfig);
                    if (!$_logPath) {
                        $this->_stopProfiling($_dataflowCode);
                        continue;
                    }

                    $_archiveLogPath = "{$_logPath}/archive_{$this->_date}";

                    $_io = new Varien_Io_File();
                    $_io->open(array('path' => $_logPath));

                    $this->_log("\tCreate archive dir {$_archiveLogPath}");
                    $_io->checkAndCreateFolder($_archiveLogPath);

                    foreach ($_io->ls(Varien_Io_File::GREP_FILES) as $_file) {
                        $this->_log("\t\tFile {$_file['text']}");
                        if (preg_match("#(?<!archive)_{$this->_date}#", $_file['text'])) {
                            $this->_log("\t\t   moved into archive dir");
                            $_io->mv("{$_logPath}/{$_file['text']}", "{$_logPath}/archive_{$this->_date}/{$_file['text']}");
                        }
                    }

                    $this->_log("\tCompress archive dir");
                    $archive = new Zend_Filter_Compress_Zip(array('archive' => "{$_archiveLogPath}.zip"));
                    $archive->compress($_archiveLogPath);

                    $this->_log("\tRemove archive dir");
                    $_io->rmdir($_archiveLogPath, true);
                } catch (Exception $e) {
                    $this->_log($e->getMessage());
                    $this->_logException($e);
                }
            }

            $this->_stopProfiling($_dataflowCode);
        }

        return $this;
    }


}