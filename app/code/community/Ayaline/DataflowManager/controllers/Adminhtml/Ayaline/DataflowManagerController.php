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

class Ayaline_DataflowManager_Adminhtml_Ayaline_DataflowManagerController extends Mage_Adminhtml_Controller_Action
{

    protected function _construct()
    {
        parent::_construct();
        $this->setUsedModuleName('Ayaline_DataflowManager');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('ayaline_dataflowmanager/system_config')->dataflowManagementIsAllowed('view');
    }

    /**
     * @return $this
     */
    protected function _initAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('system');
        $this->_title($this->__('System'))->_title($this->__('Dataflow Manager'));

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

} 