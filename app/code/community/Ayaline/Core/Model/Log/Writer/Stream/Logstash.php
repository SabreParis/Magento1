<?php

/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_Core_Model_Log_Writer_Stream_Logstash extends Zend_Log_Writer_Abstract
{

    /**
     * Holds the PHP stream to log to.
     *
     * @var null|resource
     */
    protected $_stream = null;

    /**
     * Holds the PHP stream to log to (resource to native log).
     *
     * @var null|resource
     */
    protected $_origStream = null;

    /**
     * Class Constructor
     *
     * @param array|string|resource $streamOrUrl Stream or URL to open as a stream
     * @param string|null           $mode        Mode, only applicable if a URL is given
     * @throws Zend_Log_Exception
     */
    public function __construct($streamOrUrl, $mode = null)
    {
        // dirty simplification

        // Setting the default
        if (null === $mode) {
            $mode = 'a';
        }

        // native stream log
        if (!$this->_origStream = @fopen($streamOrUrl, $mode, false)) {
            #require_once 'Zend/Log/Exception.php';
            $msg = "\"$streamOrUrl\" cannot be opened with mode \"$mode\"";
            throw new Zend_Log_Exception($msg);
        }

        // custom stream log
        $streamOrUrlPathInfo = pathinfo($streamOrUrl);
        $streamOrUrl = $streamOrUrlPathInfo['dirname'] . DS . "logstash_{$streamOrUrlPathInfo['basename']}";
        if (!$this->_stream = @fopen($streamOrUrl, $mode, false)) {
            #require_once 'Zend/Log/Exception.php';
            $msg = "\"$streamOrUrl\" cannot be opened with mode \"$mode\"";
            throw new Zend_Log_Exception($msg);
        }

        $this->_formatter = new Zend_Log_Formatter_Simple();
    }

    /**
     * Write a message to the log.
     *
     * @param array $event event data
     * @return void
     * @throws Zend_Log_Exception
     */
    protected function _write($event)
    {
        $line = $this->_formatter->format($event);

        if (false === @fwrite($this->_origStream, $line)) {
            #require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception("Unable to write to stream");
        }

        $data = array(
            '@timestamp'  => date('c', strtotime($event['timestamp'])),
            '@version'    => "1",
            'level'       => $event['priority'],
            'levelName'       => $event['priorityName'],
            'storeCode'   => Mage::app()->getStore()->getCode(),
            'message'     => $event['message'],
        );

        if (false === @fwrite($this->_stream, Mage::helper('core')->jsonEncode($data) . PHP_EOL)) {
            #require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception("Unable to write to stream");
        }
    }

    /**
     * Create a new instance of Zend_Log_Writer_Stream
     *
     * @param array|Zend_Config $config
     * @return Zend_Log_Writer_Stream
     */
    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        $config = array_merge(array(
                                  'stream' => null,
                                  'mode'   => null,
                              ), $config);

        $streamOrUrl = isset($config['url']) ? $config['url'] : $config['stream'];

        return new self($streamOrUrl, $config['mode']);
    }

    /**
     * Close the stream resource.
     *
     * @return void
     */
    public function shutdown()
    {
        if (is_resource($this->_origStream)) {
            fclose($this->_origStream);
        }
        if (is_resource($this->_stream)) {
            fclose($this->_stream);
        }
    }

}
