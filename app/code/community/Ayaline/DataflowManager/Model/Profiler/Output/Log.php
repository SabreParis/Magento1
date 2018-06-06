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
class Ayaline_DataflowManager_Model_Profiler_Output_Log extends Magento_Profiler_OutputAbstract
{

    /**
     * @var string
     */
    protected $_filename;

    protected $_enableConsoleOutput = false;

    /**
     * Start output buffering
     *
     * @param string      $filename Target file to save CSV data
     * @param string|null $filter   Pattern to filter timers by their identifiers (SQL LIKE syntax)
     */
    public function __construct($filename, $filter = null)
    {
        parent::__construct($filter);

        $this->_filename = $filename;
    }

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
     * Display profiling results
     */
    public function display()
    {
        $maxColumnLength = 0;
        foreach ($this->_getTimers() as $_timerId) {
            $maxColumnLength = max(strlen($this->_renderColumnValue($_timerId, 'timer_id')), $maxColumnLength);
        }
        $maxColumnLength = ceil($maxColumnLength * 1.10);
        $separatorLine = '+' . str_repeat('-', ($maxColumnLength + 1)) . str_repeat('+' . str_repeat('-', 26), (count($this->_getColumns()) - 1)) . '+';


        $toDisplay = "\n{$this->_renderCaption()}\n";

        $header = "\n" . $separatorLine . "\n";
        foreach ($this->_getColumns() as $_columnLabel => $_columnId) {
            if ($_columnId == 'timer_id') {
                $header .= sprintf("| %-{$maxColumnLength}s|", $_columnLabel);
            } else {
                $header .= sprintf(' %-25s|', $_columnLabel);
            }
        }
        $header .= "\n" . $separatorLine . "\n";
        $toDisplay .= $header;

        $body = "";
        foreach ($this->_getTimers() as $_timerId) {
            $timerMessage = "";
            foreach ($this->_getColumns() as $_columnLabel => $_columnId) {
                if ($_columnId == 'timer_id') {
                    $timerMessage .= sprintf("| %-{$maxColumnLength}s|", $this->_renderColumnValue($_timerId, $_columnId));
                } else {
                    $timerMessage .= sprintf('%25s |', $this->_renderColumnValue($_timerId, $_columnId));
                }
            }
            $body .= $timerMessage . "\n";
        }

        $toDisplay .= $body;
        $toDisplay .= $separatorLine . "\n";

        Mage::helper('ayaline_dataflowmanager/log')->log($toDisplay, $this->_filename, Ayaline_DataflowManager_Helper_Log::PROFILE, $this->_enableConsoleOutput);
    }

}