<?php

/**
 * created : 2013
 *
 * @category  Ayaline
 * @package   Ayaline_EnhancedAdmin
 * @author    aYaline
 * @copyright Ayaline - 2013 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_EnhancedAdmin_Adminhtml_Ayaline_SetupController extends Mage_Adminhtml_Controller_Action
{

    protected function _construct()
    {
        parent::_construct();
        $this->setUsedModuleName('Ayaline_EnhancedAdmin');
        // commented because it's cause performance issues
//        Mage::helper('ayaline_enhancedadmin')->reinitConfigCache(); //  reinit config cache for all actions in this controller
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('view');
    }

    /**
     * @return $this
     */
    protected function _initAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('system');
        $this->_title($this->__('System'))->_title($this->__('Setup Management'));

        return $this;
    }

    /**
     * @return Ayaline_EnhancedAdmin_Model_Module
     */
    protected function _initModule()
    {
        $id = $this->getRequest()->getParam('id', false);
        $_module = Mage::getModel('ayaline_enhancedadmin/module')->load($id);

        if (!$_module->getId()) {
            Mage::throwException($this->__('Module does not exists'));
        }

        Mage::register('ayaline_enhancedadmin_module', $_module);

        return $_module;
    }

    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    public function viewAction()
    {
        try {
            $_module = $this->_initModule();
            $this
                ->_initAction()
                ->_title($this->__('Module %s (%s)', $_module->getName(), $_module->getVersion()))
                ->renderLayout();
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*/');
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('An error occurred, please try again later.'));
            $this->_redirect('*/*/');
        }
    }

    public function applyAction()
    {
        $_moduleId = false;
        try {
            $_module = $this->_initModule();
            $_moduleId = $_module->getId();

            $_setupCode = $this->getRequest()->getParam('code', false);
            $_version = $this->getRequest()->getParam('file', false);
            $_type = $this->getRequest()->getParam('type', Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_BOTH);

            if ($_setupCode && $_version) {
                if ($_result = $_module->applySpecificVersion($_setupCode, $_version, $_type)) {
                    if (count($_result['errors'])) {
                        foreach ($_result['errors'] as $_error) {
                            $this->_getSession()->addError($_error);
                        }
                    } else {
                        $this->_getSession()->addSuccess($this->__('Setup %s for resource "%s", applied.', $_version, $_setupCode));
                    }
                } else {
                    Mage::throwException($this->__('No setup files or no database connection.'));
                }
            } else {
                Mage::throwException($this->__('Missing parameters.'));
            }

            $this->_redirect('*/*/view', array('id' => $_moduleId, '_current' => true));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $_moduleId
                ? $this->_redirect('*/*/view', array('id' => $_moduleId, '_current' => true))
                : $this->_redirect('*/*/', array('_current' => true));
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('An error occurred, please try again later.'));
            $_moduleId
                ? $this->_redirect('*/*/view', array('id' => $_moduleId, '_current' => true))
                : $this->_redirect('*/*/', array('_current' => true));
        }
    }

    public function applyModuleUpdatesAction()
    {
        $_moduleId = false;
        try {
            $_module = $this->_initModule();
            $_moduleId = $_module->getId();

            $_type = $this->getRequest()->getParam('type', false);

            if ($_type === Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_SQL) {
                $_module->applyAllDbModuleUpdates();
                Mage::helper('ayaline_enhancedadmin')->reinitConfigCache();
            } elseif ($_type === Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_DATA) {
                $_module->applyAllDataModuleUpdates();
            } else {
                $_module->applyAllDbModuleUpdates();
                Mage::helper('ayaline_enhancedadmin')->reinitConfigCache();
                $_module->applyAllDataModuleUpdates();
            }

            $this->_getSession()->addSuccess($this->__('Module updates applied.'));

            $this->_redirect('*/*/view', array('id' => $_moduleId));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $_moduleId ? $this->_redirect('*/*/view', array('id' => $_moduleId)) : $this->_redirect('*/*/');
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('An error occurred, please try again later.'));
            $_moduleId ? $this->_redirect('*/*/view', array('id' => $_moduleId)) : $this->_redirect('*/*/');
        }
    }

    public function applyAllUpdatesAction()
    {
        try {
            $_type = $this->getRequest()->getParam('type', false);

            if ($_type === Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_SQL) {
                Mage_Core_Model_Resource_Setup::applyAllUpdates();
                Mage::helper('ayaline_enhancedadmin')->reinitConfigCache();
            } elseif ($_type === Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_DATA) {
                Mage::getResourceSingleton('ayaline_enhancedadmin/module')->forceApplyAllDataUpdates();
            } else {
                Mage_Core_Model_Resource_Setup::applyAllUpdates();
                Mage::helper('ayaline_enhancedadmin')->reinitConfigCache();
                Mage_Core_Model_Resource_Setup::applyAllDataUpdates();
            }

            $this->_getSession()->addSuccess($this->__('All updates applied.'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('An error occurred, please try again later.'));
        }
        $this->_redirect('*/*/');
    }

    public function applyUnAppliedUpdatesAction()
    {
        try {

            if ($this->getRequest()->getParam('id', false)) {
                $_module = $this->_initModule();
                Mage::getResourceSingleton('ayaline_enhancedadmin/resourceSetup')->applyUnAppliedSetupsByModule($_module);
            } else {
                Mage::getResourceSingleton('ayaline_enhancedadmin/resourceSetup')->applyUnAppliedSetups();
            }

            $this->_getSession()->addSuccess($this->__('All updates applied.'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('An error occurred, please try again later.'));
        }
        $this->_redirectReferer();
    }

    public function viewFileAction()
    {
        if (!$this->getRequest()->isAjax()) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            $this->_redirect('');

            return;
        }

        try {
            $_module = $this->_initModule();

            $_setupCode = $this->getRequest()->getParam('code', false);
            $_version = $this->getRequest()->getParam('file', false);
            $_type = $this->getRequest()->getParam('type', Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_BOTH);

            if ($_setupCode && $_version) {
                $_filesContent = $_module->getFilesContent($_setupCode, $_version, $_type);
                Mage::register('ayaline_enhancedadmin_files_content', $_filesContent);
            } else {
                Mage::throwException($this->__('Missing parameters.'));
            }

        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('An error occurred, please try again later.'));
        }

        $this->loadLayout()->renderLayout();
    }

    public function flushClassAction()
    {
        try {
            Mage::helper('ayaline_enhancedadmin/setup')->flushClasses();

            $this->_getSession()->addSuccess($this->__('Classes flushed.'));

        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('An error occurred, please try again later.'));
        }
        $this->_redirectReferer();
    }

    public function checkSetupsAction()
    {
        try {
            if ($this->getRequest()->getParam('id', false)) {
                $_module = $this->_initModule();
                Mage::getResourceSingleton('ayaline_enhancedadmin/resourceSetup')->checkSetupsByModule($_module);
            } else {
                Mage::getResourceSingleton('ayaline_enhancedadmin/resourceSetup')->checkSetups();
            }

            $this->_getSession()->addSuccess($this->__('Setups checked.'));

        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('An error occurred, please try again later.'));
        }

        $this->_redirectReferer();
    }

    public function resourceGridAction()
    {
        try {
            $this->_initModule();
            $this->loadLayout()->renderLayout();
        } catch (Mage_Core_Exception $e) {
            $this->getResponse()->setBody($e->getMessage());
        } catch (Exception $e) {
            Mage::logException($e);
            $this->getResponse()->setBody($this->__('An error occurred, please try again later.'));
        }
    }

    public function actionsAction()
    {
        try {
            if ($this->getRequest()->getParam('id', false)) {
                $this->_initModule();
            }
            $this->loadLayout()->renderLayout();
        } catch (Mage_Core_Exception $e) {
            $this->getResponse()->setBody($e->getMessage());
        } catch (Exception $e) {
            Mage::logException($e);
            $this->getResponse()->setBody($this->__('An error occurred, please try again later.'));
        }
    }

}
