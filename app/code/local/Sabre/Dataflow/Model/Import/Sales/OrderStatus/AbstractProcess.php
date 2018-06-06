<?php

/**
 * Created : 2015
 * 
 * @category Ayaline
 * @package Ayaline_XXXX
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
abstract class Sabre_Dataflow_Model_Import_Sales_OrderStatus_AbstractProcess
{

    protected $_defaultTabLevel = 0;
    protected $_processTrace = array();
    protected $_processTraceLastNum = -1;

    final protected function _resetProcessTrace()
    {
        $this->_processTrace = array();
    }

    /**
     * 
     * @param string $text
     * @param int $tabLevel
     */
    final protected function _addTrace($text, $tabLevel = 0)
    {
        if (!is_array($this->_processTrace)) {
            $this->_resetProcessTrace();
        }

        $tab = str_repeat("\t", (int) ($tabLevel + $this->_defaultTabLevel));

        $this->_processTraceLastNum++;

        $this->_processTrace[$this->_processTraceLastNum] = $tab . " |_ " . $text;

        return $this->_processTraceLastNum;
    }

    /**
     * 
     * @param string $text
     * @param string $sep
     */
    final protected function _prependTrace($key, $text, $sep = ' ')
    {
        if (is_array($this->_processTrace) && array_key_exists($key, $this->_processTrace)) {
            $origData = $this->_processTrace[$key];
            $this->_processTrace[$key] = $text . $sep . $origData;
        }
    }

    /**
     * 
     * @param string $text
     * @param string $sep
     */
    final protected function _appendTrace($key, $text, $sep = ' ')
    {
        if (is_array($this->_processTrace) && array_key_exists($key, $this->_processTrace)) {
            $origData = $this->_processTrace[$key];
            $this->_processTrace[$key] = $origData . $sep . $text;
        }
    }

    /**
     * 
     * @return array
     */
    public function getProcessTrace()
    {

        return $this->_processTrace;
    }
}
