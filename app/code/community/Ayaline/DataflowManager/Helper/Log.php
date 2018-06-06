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
class Ayaline_DataflowManager_Helper_Log extends Ayaline_DataflowManager_Helper_Data
{

    const PROFILE = 9;

    /**
     * Loggers per file
     *
     * @var array
     */
    protected $_loggers = array();

    /**
     * Unique ids per log file
     *
     * @var array
     */
    protected $_uniqueIds = array();

    /**
     * Log helper
     *  same as Mage::log except, no configuration test and add a unique process id
     *
     * @param string       $message
     * @param string       $file
     * @param integer|null $level (see Zend_Log::XXX)
     * @param bool         $enableConsoleOutput
     */
    public function log($message, $file, $level = null, $enableConsoleOutput = false)
    {
        $level = ($level === null) ? Zend_Log::INFO : $level;

        try {
            if (!isset($this->_loggers[$file])) {
                $format = '|%uid%|' . Zend_Log_Formatter_Simple::DEFAULT_FORMAT . PHP_EOL;
                $formatter = new Zend_Log_Formatter_Simple($format);
                /** @var Ayaline_DataflowManager_Model_Writer_Stream $writer */
                $writer = Mage::getModel('ayaline_dataflowmanager/writer_stream', $file);
                $writer->setFormatter($formatter);
                $writer->setEnableConsoleOutput($enableConsoleOutput);

                $this->_loggers[$file] = new Zend_Log($writer);

                $this->_loggers[$file]->addPriority('Profile', self::PROFILE);
                $this->_uniqueIds[$file] = Mage::helper('core')->getRandomString(5, Mage::helper('core')->uniqHash());
            }

            if (is_array($message) || is_object($message)) {
                $message = print_r($message, true);
            }

            $this->_loggers[$file]->log($message, $level, array('uid' => $this->_uniqueIds[$file]));
        } catch (Exception $e) {
            // silent
        }
    }

} 