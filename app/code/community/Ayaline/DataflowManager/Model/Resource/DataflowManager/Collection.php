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
class Ayaline_DataflowManager_Model_Resource_DataflowManager_Collection extends Ayaline_Core_Model_Resource_Collection_Abstract
{

    /**
     * @param null|Varien_Simplexml_Element $node
     * @param null|string                   $parentCode
     * @return $this
     * @throws Exception
     */
    protected function _getDataflowManager($node = null, $parentCode = null)
    {
        $node = ($node === null) ? Mage::getSingleton('ayaline_dataflowmanager/config')->getScriptsConfig()->getNode() : $node;

        foreach ($node as $_dataflowCode => $_dataflowConfig) {
            $_dataflowCode = ($parentCode === null) ? $_dataflowCode : "{$parentCode}/{$_dataflowCode}";
            if (!($_dataflowConfig->name || $_dataflowConfig->model)) { // name & model are mandatory
                $this->_getDataflowManager($_dataflowConfig, $_dataflowCode);
            } else {
                $res = trim(shell_exec(str_replace("%SCRIPT%", $_dataflowCode, Ayaline_DataflowManager_Model_Abstract::SEARCH_PROCESS_PATTERN)));

                $_dataflow = array(
                    'id'     => $_dataflowCode,
                    'code'   => $_dataflowCode,
                    'name'   => $_dataflowConfig->name->__toString(),
                    'model'  => $_dataflowConfig->model->__toString(),
                    'status' => Ayaline_DataflowManager_Model_System_Source_Status::STATUS_NOT_RUNNING,
                );


                if ($res >= 1) {
                    $_dataflow['status'] = Ayaline_DataflowManager_Model_System_Source_Status::STATUS_RUNNING;
                }

                $this->addItem(new Varien_Object($_dataflow));
            }
        }

        return $this;
    }

    /**
     * @param bool $printQuery
     * @param bool $logQuery
     * @return $this|Varien_Data_Collection
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        $this->_getDataflowManager();
        $this->_renderFilters()->_renderOrders()->_renderLimit();
        $this->_setIsLoaded(true);

        return $this;
    }

} 