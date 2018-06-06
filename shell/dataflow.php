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
require_once 'abstract.php';

ini_set('memory_limit', '512M');

class Ayaline_Shell_DataflowManager extends Mage_Shell_Abstract
{
    const PATTERN_PHP_VARIABLES_PREFIX = '#^php-#';
    const PATTERN_PHP_VARIABLES_DOT = '#---#';

    /**
     * @return $this
     */
    protected function _overloadPhpVariables()
    {
        foreach ($this->_args as $_key => $_value) {
            if (preg_match(self::PATTERN_PHP_VARIABLES_PREFIX, $_key)) {
                $_varName = preg_replace(
                    array(self::PATTERN_PHP_VARIABLES_PREFIX, self::PATTERN_PHP_VARIABLES_DOT),
                    array('', '.'),
                    $_key
                );

                @ini_set($_varName, $_value);

                unset($this->_args[$_key]);
            }
        }

        return $this;
    }

    protected function _list()
    {
        $lineSeparator = '+' . str_repeat(str_repeat('-', 41) . '+', 3);
        echo "\n" . $lineSeparator . "\n";
        echo sprintf('| %-40s| ', 'Data flow code') . sprintf('%-40s| ', 'Data flow name') . sprintf('%-40s|', 'Status');
        echo "\n" . $lineSeparator . "\n";
        $this->_listDataFlowCode(Mage::getSingleton('ayaline_dataflowmanager/config')->getScriptsConfig()->getNode());
        echo $lineSeparator . "\n";
    }

    /**
     * @param Varien_Simplexml_Element $node
     * @param null|string              $parentCode
     * @return void
     */
    protected function _listDataFlowCode($node, $parentCode = null)
    {
        foreach ($node as $_dataflowCode => $_dataflowConfig) {
            $_dataflowCode = ($parentCode === null) ? $_dataflowCode : "{$parentCode}/{$_dataflowCode}";
            if (!($_dataflowConfig->name || $_dataflowConfig->model)) { // name & model are mandatory
                $this->_listDataFlowCode($_dataflowConfig, $_dataflowCode);
            } else {
                $res = trim(shell_exec(str_replace("%SCRIPT%", $_dataflowCode, Ayaline_DataflowManager_Model_Abstract::SEARCH_PROCESS_PATTERN)));
                echo sprintf('| %-40s|', $_dataflowCode);
                echo sprintf(' %-40s|', $_dataflowConfig->name->__toString());
                $_status = "Is not running";
                if ($res >= 1) {
                    $_status = "Is currently running";
                    if ($res > 1) {
                        $_status .= " ({$res} processes)";
                    }
                }
                echo sprintf(' %-40s|', $_status);
                echo "\n";
            }
        }
    }

    /**
     * Check is show usage help
     *
     */
    protected function _showHelp()
    {
        if (!isset($this->_args[Ayaline_DataflowManager_Model_Abstract::DATAFLOW_PARAM_KEY])) {
            if (isset($this->_args['h']) || isset($this->_args['help'])) {
                die($this->usageHelp());
            }
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     * @return string
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f dataflow.php --data_flow <data_flow_code> --<option_key> <option_value>
        php -f dataflow.php --data_flow <data_flow_code> --php-<php_option_key> <php_option_value>
        php -f dataflow.php list

  list                                          List available data flow (and status)
  help                                          This help
  --data_flow <data_flow_code>                  Execute data flow
  --data_flow <data_flow_code> --help           Data flow help
  --php-<php_option_key> <php_option_value>     Set PHP directives (via ini_set)
  --console                                     Display log in console

  <data_flow_code>      Data flow code (see list command)
  <option_key>          Name of an option for data flow
  <option_value>        Value of data flow's option
  php-<php_option_key>  PHP directive (ex: --php-memory_limit). Dot (.) must be replaced by three dashes (---) (ex: --php-date---timezone).
  <php_option_value>    PHP directive value

USAGE;
    }

    /**
     * Run script
     */
    public function run()
    {
        if ($this->getArg('list')) {

            $this->_list();

        } elseif ($dataflowCode = $this->getArg(Ayaline_DataflowManager_Model_Abstract::DATAFLOW_PARAM_KEY)) {
            echo "Overload PHP variables...\n";
            $this->_overloadPhpVariables();

            echo "Load data flow config...\n";
            $dataflowConfig = Mage::getSingleton('ayaline_dataflowmanager/config')->getScriptConfig($dataflowCode);
            if (!$dataflowConfig) {
                die("##########\n\tInvalid data flow code\n##########\n");
            }

            echo "Init data flow model ({$dataflowConfig->model->__toString()})...\n";
            /** @var $dataflow Ayaline_DataflowManager_Model_Abstract */
            $dataflow = Mage::getModel($dataflowConfig->model->__toString());

            echo "Begin data flow process - " . now() . "\n";
            echo "----------------------------------------------------------------------------------------------------\n\n";
            $dataflow->execute($this->_args);
            echo "\n----------------------------------------------------------------------------------------------------\n";
            echo "Data flow process ended - " . now() . "\n";
        } else {
            echo $this->usageHelp();
        }
    }
}

$shell = new Ayaline_Shell_DataflowManager();
$shell->run();
