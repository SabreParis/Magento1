<?php

/**
 * created: 2014
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

class Ayaline_DataflowManager_Model_Writer_Stream extends Zend_Log_Writer_Stream
{
    /**
     * @var bool
     */
    protected $_enableConsoleOutput = false;

    /**
     * @param bool $flag
     * @return $this
     */
    public function setEnableConsoleOutput($flag = true)
    {
        $this->_enableConsoleOutput = $flag;

        return $this;
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     * @throws Zend_Log_Exception
     */
    protected function _write($event)
    {
        $line = $this->_formatter->format($event);

        if ($this->_enableConsoleOutput) {
            echo $line;
        }

        if (false === @fwrite($this->_stream, $line)) {
            #require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception("Unable to write to stream");
        }
    }

} 