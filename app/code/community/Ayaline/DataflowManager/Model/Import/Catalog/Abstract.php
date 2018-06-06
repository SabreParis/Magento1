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
 

abstract class Ayaline_DataflowManager_Model_Import_Catalog_Abstract extends Ayaline_DataflowManager_Model_Import_Abstract
{

    /**
     * {@inheritdoc}
     */
    protected function _beforeExecuteScript()
    {
        parent::_beforeExecuteScript();

        $this->_log("Set indexers to Manual Mode");
        Mage::helper('ayaline_dataflowmanager/catalog')->setIndexerToManual($this->_getIndexers());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterExecuteScript()
    {
        parent::_afterExecuteScript();

        $this->_log("Restore indexers Mode");
        Mage::helper('ayaline_dataflowmanager/catalog')->restoreIndexerMode();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _catchExecuteScriptException($e)
    {
        parent::_catchExecuteScriptException($e);

        $this->_log("Restore indexers Mode (exception case)");
        Mage::helper('ayaline_dataflowmanager/catalog')->restoreIndexerMode();

        return $this;
    }

    /**
     * @return mixed
     */
    protected function _getIndexers()
    {
        return $this->_getScriptConfig()->indexers->asArray();
    }

}