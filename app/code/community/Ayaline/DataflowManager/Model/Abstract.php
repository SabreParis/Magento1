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

/**
 * Class Ayaline_DataflowManager_Model_Abstract
 */
abstract class Ayaline_DataflowManager_Model_Abstract
{

    const SEARCH_PROCESS_PATTERN = "ps auxwww | grep %SCRIPT% | grep php | grep -v 'blackfire' | grep -v 'grep' | grep -v '\-c' | wc -l"; // grep -v '\-c' => -c means via crontab
    const DATAFLOW_PARAM_KEY = 'data_flow';
    const CONSOLE_OUTPUT_PARAM_KEY = 'console';

    /**
     * Script arguments
     *
     * @var array
     */
    private $__args = array();

    /**
     * @var DateTime
     */
    private $__beginTime;

    /**
     * @var DateTime
     */
    private $__endTime;

    /**
     * Log file name
     *
     * @var string
     */
    private $__logFilename;

    /**
     * Exception file name
     *
     * @var string
     */
    private $__logExceptionFilename;

    /**
     * Script config
     *
     * @var SimpleXMLElement
     */
    private $__scriptConfig;


    /**
     * Script code (used for retrieving data flow config, log folder, import/export folders etc)
     *
     * @var string
     */
    protected $_scriptCode = '';

    /**
     * Flag to send email or not at the end of the script
     *
     * @var bool
     */
    protected $_sendEmail = true;

    /**
     * Flag to add or not log files to email
     *
     * @var bool
     */
    protected $_emailAttachLog = false;

    /**
     * Define email configuration for the script (default: 'email')
     *  set only group name in config path (ex: ayaline_dataflowmanager/CUSTOM_GROUP/recipient_email)
     *
     * @var string
     */
    protected $_emailConfiguration = 'email';

    /**
     * Data for email
     *
     * @var array
     */
    protected $_emailTemplateParams = array();

    /**
     * Email attachments'
     *
     * @var array
     */
    protected $_emailAttachments = array();

    /**
     * Flag to avoid multiple script execution
     *
     * @var bool
     */
    protected $_avoidMultipleExecution = true;

    /**
     * Flag to enable profiler
     *
     * @var bool
     */
    protected $_enableProfiler = true;

    /**
     * Flag to enable output to console
     *
     * @var bool
     */
    protected $_enableConsoleOutput = false;


    ####################################
    #####    Abstract functions    #####
    ####################################

    /**
     * Script Documentation
     * --help argument
     *
     * @return string
     */
    abstract protected function _getDocumentation();

    /**
     * Check if script can be executed
     *
     * @return bool
     */
    abstract protected function _validate();

    /**
     * Script entry point
     *
     * @return $this
     */
    abstract protected function _executeScript();

    ################################
    #####    Init functions    #####
    ################################

    /**
     * @return $this
     * @throws Mage_Core_Exception
     */
    private function __canExecute()
    {
        if ($this->_avoidMultipleExecution) {
            // search process for current script
            $res = trim(shell_exec(str_replace("%SCRIPT%", $this->_scriptCode, self::SEARCH_PROCESS_PATTERN)));
            if ($res > 1) { // one means it self, more than one means multiple process
                $this->_sendEmail = false;
                $script = get_class($this);
                Mage::throwException("Script '{$script}' already running (nb process: {$res})");
            }
        }

        if ($this->_getScriptConfig()->exclude_script) {
            /** @var $_excludeScript Varien_Simplexml_Element */
            foreach ($this->_getScriptConfig()->exclude_script->children() as $_excludeScript) {
                $res = trim(shell_exec(str_replace("%SCRIPT%", $_excludeScript->__toString(), self::SEARCH_PROCESS_PATTERN)));
                if ($res >= 1) { // one or more means script running
                    $this->_sendEmail = false;
                    Mage::throwException("Exclude script '{$_excludeScript}' already running (nb process: {$res})");
                }
            }
        }

        return $this;
    }

    /**
     * Init log files (log and exception)
     */
    private function __initLogFiles()
    {
        try {
            $logPath = Mage::getSingleton('ayaline_dataflowmanager/system_config')->getLogPath();
            if ($logPath) {
                if (Mage::getSingleton('ayaline_dataflowmanager/system_config')->useRelativePath()) {
                    $logPath = Mage::getBaseDir() . $logPath;
                }
            } else { // no config log path, but we need to trace information for debug this case
                $logPath = Mage::getBaseDir('log'); // use default Magento log dir
            }

            $logPath .= "/{$this->_scriptCode}";

            $io = new Varien_Io_File();
            $io->setAllowCreateFolders(true);
            $io->checkAndCreateFolder($io->getCleanPath($logPath), 0755);

            $now = now(true);
            $logFilename = "trace_{$now}.log";
            $logExceptionFilename = "exception_{$now}.log";

            $this->__logFilename = $io->getCleanPath("{$logPath}/{$logFilename}");
            $this->__logExceptionFilename = $io->getCleanPath("{$logPath}/{$logExceptionFilename}");

            $this->_addEmailTemplateParams('log_filename', $this->__logFilename);
            $this->_addEmailTemplateParams('log_exception_filename', $this->__logExceptionFilename);
        } catch (Exception $e) {
            echo "{$e->getMessage()}\n";
            echo "\n{$e->__toString()}\n\n";
            exit;
        }
    }

    private function __begin()
    {
        $this->__beginTime = new DateTime();
        $this->_addEmailTemplateParams('begin_time', $this->__beginTime->format(Varien_Date::DATETIME_PHP_FORMAT));
        $this->_addEmailTemplateParams('extra_params', '');
    }

    private function __end()
    {
        $this->__endTime = new DateTime();
        $this->_addEmailTemplateParams('end_time', $this->__endTime->format(Varien_Date::DATETIME_PHP_FORMAT));
    }

    /**
     * Display doc if help or h param exist or if $force is true
     *
     * @param bool $force
     */
    private function __displayDoc($force = false)
    {
        if ($this->_getArgument('help', false) || $this->_getArgument('h', false) || $force) {
            echo "{$this->_getDocumentation()}\n\n{$this->_getCommonDocumentation()}\n";
            exit;
        }
    }

    /**
     * Check if script can be executed
     */
    private function __validate()
    {
        if (!$this->_validate()) {
            $this->__displayDoc(true);
        }
    }

    /**
     * Init script configuration (loaded from dataflow.xml)
     */
    private function __initScriptConfig()
    {
        try {
            $scriptConfig = Mage::getSingleton('ayaline_dataflowmanager/config')->getScriptConfig($this->_scriptCode);

            if (!$scriptConfig) {
                Mage::throwException("Can't find script configuration");
            }

            $this->__scriptConfig = $scriptConfig;
        } catch (Exception $e) {
            echo "{$e->getMessage()}\n";
            echo "\n{$e->__toString()}\n\n";
            exit;
        }
    }

    /**
     * Init Profiler
     *
     * @return $this
     */
    private function __initProfiler()
    {
        if ($this->_enableProfiler) {
            /** @var Ayaline_DataflowManager_Model_Profiler_Output_Log $output */
            $output = Mage::getModel('ayaline_dataflowmanager/profiler_output_log', $this->__logFilename);
            $output->setEnableConsoleOutput($this->_enableConsoleOutput);

            Magento_Profiler::enable();
            Magento_Profiler::registerOutput($output);
        }

        return $this;
    }

    #########################################
    #####    Documentation functions    #####
    #########################################

    protected function _getCommonDocumentation()
    {
        $doc = <<<DOC
Common arguments:
-h, --help      Display documentation
--console       Display log into console
DOC;
        return $doc;
    }

    ###############################
    #####    Log functions    #####
    ###############################

    /**
     * @param mixed    $message
     * @param null|int $level (see Zend_Log::XXX)
     */
    protected function _log($message, $level = null)
    {
        Mage::helper('ayaline_dataflowmanager/log')->log($message, $this->__logFilename, $level, $this->_enableConsoleOutput);
    }

    /**
     * @param Exception $e
     */
    protected function _logException($e)
    {
        Mage::helper('ayaline_dataflowmanager/log')->log("\n{$e->__toString()}", $this->__logExceptionFilename, Zend_Log::ERR, $this->_enableConsoleOutput);
    }

    ####################################
    #####    Profiler functions    #####
    ####################################

    /**
     * Start profiling
     *
     * @param string $timerName
     */
    protected function _startProfiling($timerName)
    {
        Magento_Profiler::start($timerName);
    }

    /**
     * Stop profiling
     *
     * @param string $timerName
     */
    protected function _stopProfiling($timerName)
    {
        Magento_Profiler::stop($timerName);
    }

    #################################
    #####    Email functions    #####
    #################################

    /**
     * @return $this
     */
    private function __sendEmail()
    {
        if (!$this->_sendEmail) {
            $this->_log("Do not send email");

            return $this;
        }

        $this->_startProfiling(__FUNCTION__);

        if ($this->_emailAttachLog) {
            $this->_addAttachment(
                file_get_contents($this->__logFilename),
                Zend_Mime::TYPE_OCTETSTREAM,
                Zend_Mime::DISPOSITION_ATTACHMENT,
                Zend_Mime::ENCODING_BASE64,
                pathinfo($this->__logFilename, PATHINFO_BASENAME)
            );

            $this->_addAttachment(
                file_get_contents($this->__logExceptionFilename),
                Zend_Mime::TYPE_OCTETSTREAM,
                Zend_Mime::DISPOSITION_ATTACHMENT,
                Zend_Mime::ENCODING_BASE64,
                pathinfo($this->__logExceptionFilename, PATHINFO_BASENAME)
            );
        }

        try {
            $this->_log("Send email");

            $recipients = Mage::getSingleton('ayaline_dataflowmanager/system_config')->getRecipientsData($this->_emailConfiguration);

            /** @var $mailer Ayaline_DataflowManager_Model_Core_Email_Template_Mailer */
            $mailer = Mage::getModel('ayaline_dataflowmanager/core_email_template_mailer');

            /** @var $emailInfo Mage_Core_Model_Email_Info */
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo($recipients['email'], $recipients['name']);
            $mailer->addEmailInfo($emailInfo);

            if ($recipients['cc_email']) {
                foreach ($recipients['cc_email'] as $_email) {
                    $emailInfo = Mage::getModel('core/email_info');
                    $emailInfo->addTo($_email);
                    $mailer->addEmailInfo($emailInfo);
                }
            }

            $mailer->setSender(Mage::getSingleton('ayaline_dataflowmanager/system_config')->getSenderData($this->_emailConfiguration));
            $mailer->setStoreId(Mage::app()->getDefaultStoreView()->getId());
            $mailer->setTemplateId(Mage::getSingleton('ayaline_dataflowmanager/system_config')->getEmailTemplate($this->_emailConfiguration));
            $mailer->setTemplateParams($this->_emailTemplateParams);
            $mailer->setAttachment($this->_emailAttachments);

            $mailer->send();

            $this->_log("Email sent");
        } catch (Exception $e) {
            $this->_log($e->getMessage(), Zend_Log::ERR);
            $this->_logException($e);
        }

        $this->_stopProfiling(__FUNCTION__);

        return $this;
    }

    /**
     * Add attachment to email
     *
     * @param string      $body
     * @param string      $mimeType
     * @param string      $disposition
     * @param string      $encoding
     * @param null|string $filename
     * @return $this
     */
    protected function _addAttachment($body, $mimeType = Zend_Mime::TYPE_OCTETSTREAM, $disposition = Zend_Mime::DISPOSITION_ATTACHMENT, $encoding = Zend_Mime::ENCODING_BASE64, $filename = null)
    {
        $mp = new Zend_Mime_Part($body);
        $mp->encoding = $encoding;
        $mp->type = $mimeType;
        $mp->disposition = $disposition;
        $mp->filename = $filename;

        $this->_emailAttachments[] = $mp;

        return $this;
    }

    /**
     * Add template params for email
     *
     * @param string $key
     * @param mixed  $value
     * @return $this
     */
    protected function _addEmailTemplateParams($key, $value)
    {
        $this->_emailTemplateParams[$key] = $value;

        return $this;
    }

    /**
     * Add extra information in email
     *
     * @param string $value
     * @param string $endLine
     * @return $this
     */
    protected function _addEmailExtraInformation($value, $endLine = '<br />')
    {
        $this->_addEmailTemplateParams('extra_params', "{$this->_emailTemplateParams['extra_params']}{$value}{$endLine}");

        return $this;
    }

    ####################################
    #####    Accessor functions    #####
    ####################################

    /**
     * Accessor to __scriptConfig
     *
     * @return SimpleXMLElement
     */
    protected function _getScriptConfig()
    {
        return $this->__scriptConfig;
    }

    /**
     * Retrieve script argument
     *
     * @param string $key
     * @param null   $default
     * @return mixed
     */
    protected function _getArgument($key, $default = null)
    {
        return array_key_exists($key, $this->__args) ? $this->__args[$key] : $default;
    }

    ##########################################
    #####    Execute script functions    #####
    ##########################################

    /**
     * @return $this
     */
    protected function _beforeExecuteScript()
    {
        $this->_addEmailTemplateParams('dataflow_name', $this->_getScriptConfig()->name->__toString());

        return $this;
    }

    /**
     * @return $this
     */
    protected function _afterExecuteScript()
    {
        return $this;
    }

    /**
     * @param Exception $e
     * @return $this
     */
    protected function _catchExecuteScriptException($e)
    {
        return $this;
    }

    /**
     * Master Entry point
     *
     * @param array $args
     */
    final public function execute(array $args)
    {
        // init via \Ayaline_Shell_DataflowManager (shell/dataflow.php)
        $this->_scriptCode = $args[self::DATAFLOW_PARAM_KEY];
        unset($args[self::DATAFLOW_PARAM_KEY]);
        $this->_enableConsoleOutput = array_key_exists(self::CONSOLE_OUTPUT_PARAM_KEY, $args) ? true : false;
        unset($args[self::CONSOLE_OUTPUT_PARAM_KEY]);

        $this->__args = $args;

        $this->__displayDoc();

        $this->__begin();

        $this->__initScriptConfig();

        $this->__initLogFiles();

        $this->__initProfiler();

        $this->__validate();

        $this->_startProfiling(__FUNCTION__);

        try {
            $this->__canExecute();

            $this->_beforeExecuteScript();

            $this->_executeScript();

            $this->_afterExecuteScript();
        } catch (Exception $e) {
            $this->_log($e->getMessage(), Zend_Log::ERR);
            $this->_logException($e);
            $this->_catchExecuteScriptException($e);
        }

        $this->_stopProfiling(__FUNCTION__);

        $this->__end();

        $this->__sendEmail();
    }

} 