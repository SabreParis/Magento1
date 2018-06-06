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
class Ayaline_Billboard_Adminhtml_Ayaline_BillboardController extends Mage_Adminhtml_Controller_Action
{

    protected function _construct()
    {
        $this->setUsedModuleName('Ayaline_Billboard');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed(Ayaline_Billboard_Model_Billboard::IS_ALLOWED_BILLBOARD . 'view');
    }

    protected function _initAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('cms');
        $this->_addBreadcrumb(Mage::helper('ayalinebillboard')->__('CMS'), Mage::helper('ayalinebillboard')->__('CMS'))->_addBreadcrumb(Mage::helper('ayalinebillboard')->__('Billboard'), Mage::helper('ayalinebillboard')->__('Billboard'));
        $this->_title(Mage::helper('ayalinebillboard')->__('CMS'))->_title(Mage::helper('ayalinebillboard')->__('Billboard'));

        return $this;
    }

    public function preDispatch()
    {
        parent::preDispatch();
        if ($this->getRequest()->getActionName() == 'index') {
            /* @var $types Ayaline_Billboard_Model_Mysql4_Billboard_Type_Collection */
            $types = Mage::getResourceSingleton('ayalinebillboard/billboard_type_collection');
            if ($types->count() == 0) {
                $url = $this->getUrl('adminhtml/ayaline_billboard_type');
                $this->_getSession()->addNotice(Mage::helper('ayalinebillboard')->__('You must create <a href="%s" title="Manage Billboard Types">kinds of billboards</a> before creating billboards.', $url));
            }
        }

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->_addBreadcrumb(Mage::helper('ayalinebillboard')->__('Manage Billboards'), Mage::helper('ayalinebillboard')->__('Manage Billboards'));
        $this->_title(Mage::helper('ayalinebillboard')->__('Manage Billboards'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->getBlock('ayaline.billboard.billboard.grid')->toHtml());
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('billboard_id');
        $model = Mage::getModel('ayalinebillboard/billboard');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->_getSession()->addError(Mage::helper('ayalinebillboard')->__('This billboard no longer exists.'));
                $this->_redirect('*/*/');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : Mage::helper('ayalinebillboard')->__('New Billboard'));

        $data = $this->_getSession()->getBillboardFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('ayaline_billboard_current_billboard_bo', $model);

        $this->_initAction()
             ->_addBreadcrumb($id ? Mage::helper('ayalinebillboard')->__('Edit Billboard') : Mage::helper('ayalinebillboard')->__('New Billboard'), $id ? Mage::helper('ayalinebillboard')->__('Edit Billboard') : Mage::helper('ayalinebillboard')->__('New Billboard'))
             ->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $data = $this->_prepareData($data);
            $id = $this->getRequest()->getParam('billboard_id');
            /* @var $model Ayaline_Billboard_Model_Billboard */
            $model = Mage::getModel('ayalinebillboard/billboard')->load($id);
            if (!$model->getId() && $id) {
                $this->_getSession()->addError(Mage::helper('ayalinebillboard')->__('This billboard no longer exists.'));
                $this->_redirect('*/*/');

                return;
            }

            $model->setData($data);

            try {
                $model->validate();
                $model->save();
                $this->_getSession()->addSuccess(Mage::helper('ayalinebillboard')->__('The billboard has been saved.'));
                $this->_getSession()->setBillboardFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('billboard_id' => $model->getId()));

                    return;
                }
                $this->_redirect('*/*/');

                return;

            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
                $this->_getSession()->setBillboardFormData($data);
                $this->_redirect('*/*/edit', array('billboard_id' => $this->getRequest()->getParam('billboard_id')));

                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('billboard_id')) {
            try {
                $model = Mage::getModel('ayalinebillboard/billboard');
                $model->load($id);
                $model->delete();
                $this->_getSession()->addSuccess(Mage::helper('ayalinebillboard')->__('The billboard has been deleted.'));
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('billboard_id' => $id));

                return;
            }
        }
        $this->_getSession()->addError(Mage::helper('ayalinebillboard')->__('Unable to find a billboard to delete.'));
        $this->_redirect('*/*/');
    }

    protected function _prepareData($data)
    {
        $data = $this->_filterDateTime($data, array('display_from', 'display_to'));

        $data['store_id'] = $data['stores'];
        $data['type_id'] = $data['types'];
        $data['customer_group_id'] = $data['customer_group_ids'];

        $dataObject = new Varien_Object($data);
        Mage::dispatchEvent('ayalinebillboard_adminhtml_billboard_prepare_data', array('billboard_data' => $dataObject));

        return $dataObject->getData();
    }

}