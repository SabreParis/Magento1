<?php
/**
 * created : 10/03/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Shop
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 * Shop admin controller
 *
 */
class Ayaline_Shop_Adminhtml_Ayaline_ShopController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Additional initialization
     *
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Ayaline_Shop');
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('system');

        $block = $this->getLayout()->createBlock('ayalineshop/adminhtml_shop', 'shop');
        $this->_addContent($block);

        $this->_addBreadcrumb($this->__('-MY WEBSITE-'), $this->__('-MY WEBSITE-'));
        $this->_addBreadcrumb($this->__('Shops'), $this->__('Shops'));
        $this->_addBreadcrumb($this->__('Manage Shops'), $this->__('Manage Shops'));
        $this->renderLayout();
    }

    protected function _initShop($idFieldName = 'id')
    {
        $this->_title($this->__('Shops'))->_title($this->__('Manage Shops'));

        $shopId = (int)$this->getRequest()->getParam($idFieldName);
        $shop = Mage::getModel('ayalineshop/shop');

        if ($shopId) {
            $shop->load($shopId);
        }

        Mage::register('current_ayaline_shop', $shop);

        return $this;
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('ayalineshop/adminhtml_shop_grid')->toHtml());
    }

    /**
     * Customer edit action
     */
    public function editAction()
    {
        $this->_initShop();
        $this->loadLayout();

        /* @var $shop Ayaline_Shop_Model_Shop */
        $shop = Mage::registry('current_ayaline_shop');

        $this->_title($shop->getId() ? $shop->getName() : $this->__('New Shop'));
        $this->renderLayout();
    }

    /**
     * Create new customer action
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Delete customer action
     */
    public function deleteAction()
    {
        $this->_initShop();
        $shop = Mage::registry('current_ayaline_shop');
        if ($shop->getId()) {
            try {
                $shop->load($shop->getId());
                $shop->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ayalineshop')->__('The shop has been deleted.'));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Save customer action
     */
    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        try {
            if ($data) {
                $redirectBack = $this->getRequest()->getParam('back', false);
                $this->_initShop('shop_id');

                /* @var $shop Ayaline_Shop_Model_Shop */
                $shop = Mage::registry('current_ayaline_shop');

                $shop->setData($data);
                $shop->save();

                if (isset ($data['picture']['delete'])) {
                    $shop->setPicture('');
                    $shop->save();
                } elseif ($_FILES['picture']['name'] != '') {
                    $shop->upload($_FILES['picture'], 'picture');
                    $shop->save();
                } else {
                    if(isset ($data['old_picture'])) {
                        $shop->setPicture($data['old_picture']);
                        $shop->save();
                    }
                }

                if (isset ($data['marker']['delete'])) {
                    $shop->setMarker('');
                    $shop->save();
                } elseif ($_FILES['marker']['name'] != '') {
                    $shop->upload($_FILES['marker'], 'marker');
                    $shop->save();
                } else {
                    if(isset ($data['old_marker'])) {
                        $shop->setMarker($data['old_marker']);
                        $shop->save();
                    }
                }


                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('ayalineshop')->__('The shop has been saved.')
                );
            }
            if ($redirectBack) {
                $this->_redirect('*/*/edit', array(
                    'id'       => $shop->getId(),
                    '_current' => true,
                ));

                return;
            }

        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            Mage::logException($e);
            $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('id' => $shop->getId())));
        } catch (Exception $e) {
            $this->_getSession()->addException($e,
                                               Mage::helper('ayalineshop')->__('An error occurred while saving the shop.'));
            $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('id' => $shop->getId())));
            Mage::logException($e);

            return;
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*/index'));
    }

    public function massDeleteAction()
    {
        $shopIds = $this->getRequest()->getParam('shop');
        if (!is_array($shopIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ayalineshop')->__('Please select shop(s).'));
        } else {
            try {
                $shop = Mage::getModel('ayalineshop/shop');
                foreach ($shopIds as $shopId) {
                    $shop->load($shopId)
                         ->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('ayalineshop')->__(
                        'Total of %d record(s) were deleted.', count($shopIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    public function validateAction()
    {
        $response = new Varien_Object();
        $response->setError(false);

        if ($response->getError()) {
            $this->_initLayoutMessages('adminhtml/session');
            $response->setMessage($this->getLayout()->getMessagesBlock()->getGroupedHtml());
        }

        $this->getResponse()->setBody($response->toJson());
    }
}
