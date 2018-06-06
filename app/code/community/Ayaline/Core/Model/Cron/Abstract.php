<?php
/**
 * created : 12/04/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Core
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 * Manage log for cron script
 *
 * @package Ayaline_Core
 */
abstract class Ayaline_Core_Model_Cron_Abstract extends Varien_Object
{

    const AYALINE_DIR = 'ayaline';

    /**
     * Path to log files
     *
     * @var string
     */
    protected $_ayalineLogPath;

    protected $_ayalineLogFilename;

    protected $_lockFile = null;
    protected $_isLocked = null;

    protected function _construct()
    {
        parent::_construct();

        // ex: {{base_dir}}/var/log/ayaline/competition
        $this->_ayalineLogPath = Mage::getBaseDir('log') . DS . self::AYALINE_DIR . DS . $this->_getLogPath();

        $this->_ayalineLogFilename = self::AYALINE_DIR . DS . $this->_getLogPath() . DS . $this->_getLogFilename();
    }

    /**
     * Close file resource if it was opened
     */
    public function __destruct()
    {
        if ($this->_lockFile) {
            fclose($this->_lockFile);
        }
    }

    /**
     * Check if cron can be processed
     *
     * @param Mage_Cron_Model_Schedule $schedule
     * @return bool
     */
    protected function _canExecute($schedule)
    {
        if ($schedule && $schedule instanceof Mage_Cron_Model_Schedule) {
            // check via cron table
            $cronSchedules = Mage::getResourceModel('cron/schedule_collection');
            $cronSchedules->addFieldToFilter('job_code', array('eq' => $schedule->getJobCode()));
            $cronSchedules->addFieldToFilter('status', array('eq' => Mage_Cron_Model_Schedule::STATUS_RUNNING));
            if ($cronSchedules->getSize()) {
                $this->_log("\tA job with the same code {$schedule->getJobCode()} is already running: {$cronSchedules->getFirstItem()->getId()}");

                return false;
            }
        }

        // check via lock file
        return !$this->_isLocked();
    }

    #####    Log Methods    #####

    /**
     * Check if log is enabled
     *
     * @return bool
     */
    abstract protected function _logIsActive();

    /**
     * Path to log files, from {{base_dir}}/var/log/ayaline
     *
     * @return string
     */
    abstract protected function _getLogPath();

    /**
     * Retrieve log filename
     *
     * @return string
     */
    abstract protected function _getLogFilename();


    /**
     * Log message
     *
     * @param mixed $message
     * @param int   $level (see Zend_Log)
     */
    protected function _log($message, $level = null)
    {
        $forceLog = $this->_logIsActive();
        if ($forceLog) {
            if (!is_dir($this->_ayalineLogPath)) {
                mkdir($this->_ayalineLogPath, 0755, true);
            }
            Mage::log($message, $level, $this->_ayalineLogFilename, $forceLog);
        }
    }

    /**
     * Log exception (in the same file as log)
     *
     * @param Exception $exception
     */
    protected function _logException($exception)
    {
        $this->_log("\n" . $exception->__toString(), Zend_Log::ERR);
    }

    #####    Lock Methods    #####

    /**
     * Get lock file resource
     *
     * @return resource
     */
    protected function _getLockFile()
    {
        if ($this->_lockFile === null) {
            $varDir = Mage::getConfig()->getVarDir('locks');
            $lockName = Mage::helper('ayalinecore')->formatUrlKey($this->_getLogPath());

            $file = $varDir . DS . "ayaline_cron_{$lockName}.lock";
            if (is_file($file)) {
                $this->_lockFile = fopen($file, 'w');
            } else {
                $this->_lockFile = fopen($file, 'x');
            }
            fwrite($this->_lockFile, date('r'));
        }

        return $this->_lockFile;
    }


    /**
     * Lock process without blocking.
     * This method allow protect multiple process running and fast lock validation.
     *
     * @return Ayaline_Core_Model_Cron_Abstract
     */
    protected function _lock()
    {
        $this->_isLocked = true;
        flock($this->_getLockFile(), LOCK_EX | LOCK_NB);

        return $this;
    }

    /**
     * Unlock process
     *
     * @return Ayaline_Core_Model_Cron_Abstract
     */
    protected function _unlock()
    {
        $this->_isLocked = false;
        flock($this->_getLockFile(), LOCK_UN);

        return $this;
    }

    /**
     * Check if process is locked
     *
     * @return bool
     */
    protected function _isLocked()
    {
        if ($this->_isLocked !== null) {
            return $this->_isLocked;
        } else {
            $fp = $this->_getLockFile();
            if (flock($fp, LOCK_EX | LOCK_NB)) {
                flock($fp, LOCK_UN);

                return false;
            }

            return true;
        }
    }

}