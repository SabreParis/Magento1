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
 * shop group controller
 *
 */
class Ayaline_Shop_Adminhtml_Ayaline_Shop_GroupController extends Mage_Adminhtml_Controller_Action
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
        $this->_setActiveMenu('jvnc');

        $block = $this->getLayout()->createBlock('ayalineshop/adminhtml_shop_group', 'shop_group');
        $this->_addContent($block);

        $this->_addBreadcrumb($this->__('Shops'), $this->__('Shops'));
        $this->_addBreadcrumb($this->__('Manage Shop Group'), $this->__('Manage Shop Group'));
        $this->renderLayout();
    }

    protected function _initGroup($idFieldName = 'id')
    {
        $this->_title($this->__('Shops'))->_title($this->__('Manage Shop Group'));

        $groupId = (int)$this->getRequest()->getParam($idFieldName);
        $group = Mage::getModel('ayalineshop/shop_group');

        if ($groupId) {
            $group->load($groupId);
        }

        Mage::register('current_ayaline_shop_group', $group);

        return $this;
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('ayalineshop/adminhtml_shop_group_grid')->toHtml());
    }

    /**
     * Customer edit action
     */
    public function editAction()
    {
        $this->_initGroup();
        $this->loadLayout();

        /* @var $group Ayaline_Shop_Model_Shop_Group */
        $group = Mage::registry('current_ayaline_shop_group');

        $this->_title($group->getId() ? $group->getName() : $this->__('New Shop Group'));
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
        $this->_initGroup('id');

        $group = Mage::registry('current_ayaline_shop_group');
        if ($group->getId()) {
            try {
                $group->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ayalineshop')->__('The group has been deleted.'));
            } catch (Exception $e) {
//                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->addError('An error occurred: the shops are they attached to the shop group?');
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
                $this->_initGroup('group_id');

                /* @var $customer Ayaline_Shop_Model_Shop_Group */
                $group = Mage::registry('current_ayaline_shop_group');
                $group->setData($data);
                $group->save();

                if (isset ($data['icon']['delete'])) {
                    $group->setIcon('');
                    $group->save();
                } elseif ($_FILES['icon']['name'] != '') {
                    $group->upload($_FILES['icon']);
                    $group->save();
                } else {
                    $group->setIcon($data['old_icon']);
                    $group->save();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('ayalineshop')->__('The group has been saved.')
                );
            }
            if ($redirectBack) {
                $this->_redirect('*/*/edit', array(
                    'id'       => $group->getId(),
                    '_current' => true,
                ));

                return;
            }

        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('id' => $group->getId())));
        } catch (Exception $e) {
            $this->_getSession()->addException($e,
                                               Mage::helper('ayalineshop')->__('An error occurred while saving the group.'));
            $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('id' => $group->getId())));

            return;
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*/index'));
    }

    public function validateAction()
    {
        $response = new Varien_Object();
        $response->setError(0);

        if ($response->getError()) {
            $this->_initLayoutMessages('adminhtml/session');
            $response->setMessage($this->getLayout()->getMessagesBlock()->getGroupedHtml());
        }

        $this->getResponse()->setBody($response->toJson());
    }


    public function massDeleteAction()
    {
        $groupIds = $this->getRequest()->getParam('groups');
        if (!is_array($groupIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ayalineshop')->__('Please select shops group.'));
        } else {
            try {
                $group = Mage::getModel('ayalineshop/shop_group');
                foreach ($groupIds as $groupId) {
                    $group->load($groupId)
                          ->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('ayalineshop')->__(
                        'Total of %d record(s) were deleted.', count($groupIds)
                    )
                );
            } catch (Exception $e) {
                //Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->addError('An error occurred: the shops are they attached to the shop group?');
            }
        }

        $this->_redirect('*/*/index');
    }
}
