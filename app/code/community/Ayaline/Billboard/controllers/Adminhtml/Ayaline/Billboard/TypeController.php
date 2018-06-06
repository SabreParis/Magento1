<?php
/**
 * created : 10/08/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 *
 * @package Ayaline_Billboard
 */
class Ayaline_Billboard_Adminhtml_Ayaline_Billboard_TypeController extends Mage_Adminhtml_Controller_Action
{

    protected function _construct()
    {
        $this->setUsedModuleName('Ayaline_Billboard');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed(Ayaline_Billboard_Model_Billboard_Type::IS_ALLOWED_BILLBOARD_TYPE . 'view');
    }

    protected function _initAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('cms');
        $this->_addBreadcrumb(Mage::helper('ayalinebillboard')->__('CMS'), Mage::helper('ayalinebillboard')->__('CMS'))->_addBreadcrumb(Mage::helper('ayalinebillboard')->__('Billboard'), Mage::helper('ayalinebillboard')->__('Billboard'));
        $this->_title(Mage::helper('ayalinebillboard')->__('CMS'))->_title(Mage::helper('ayalinebillboard')->__('Billboard'));

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->_addBreadcrumb(Mage::helper('ayalinebillboard')->__('Manage Billboard Types'), Mage::helper('ayalinebillboard')->__('Manage Billboard Types'));
        $this->_title(Mage::helper('ayalinebillboard')->__('Manage Billboard Types'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->getBlock('ayaline.billboard.type.grid')->toHtml());
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('type_id');
        $model = Mage::getModel('ayalinebillboard/billboard_type');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->_getSession()->addError(Mage::helper('ayalinebillboard')->__('This billboard type no longer exists.'));
                $this->_redirect('*/*/');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : Mage::helper('ayalinebillboard')->__('New Billboard Type'));

        $data = $this->_getSession()->getBillboardTypeFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('ayaline_billboard_current_type_bo', $model);

        $this->_initAction()
             ->_addBreadcrumb($id ? Mage::helper('ayalinebillboard')->__('Edit Billboard Type') : Mage::helper('ayalinebillboard')->__('New Billboard Type'), $id ? Mage::helper('ayalinebillboard')->__('Edit Billboard Type') : Mage::helper('ayalinebillboard')->__('New Billboard Type'))
             ->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $id = $this->getRequest()->getParam('type_id');
            $model = Mage::getModel('ayalinebillboard/billboard_type')->load($id);
            if (!$model->getId() && $id) {
                $this->_getSession()->addError(Mage::helper('ayalinebillboard')->__('This billboard type no longer exists.'));
                $this->_redirect('*/*/');

                return;
            }

            $model->setData($data);

            try {
                $model->save();
                $this->_getSession()->addSuccess(Mage::helper('ayalinebillboard')->__('The billboard type has been saved.'));
                $this->_getSession()->setBillboardTypeFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('type_id' => $model->getId()));

                    return;
                }
                $this->_redirect('*/*/');

                return;

            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_getSession()->setBillboardTypeFormData($data);
                $this->_redirect('*/*/edit', array('type_id' => $this->getRequest()->getParam('type_id')));

                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('type_id')) {
            try {
                $model = Mage::getModel('ayalinebillboard/billboard_type');
                $model->load($id);
                $model->delete();
                $this->_getSession()->addSuccess(Mage::helper('ayalinebillboard')->__('The billboard type has been deleted.'));
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('type_id' => $id));

                return;
            }
        }
        $this->_getSession()->addError(Mage::helper('ayalinebillboard')->__('Unable to find a billboard type to delete.'));
        $this->_redirect('*/*/');
    }


}