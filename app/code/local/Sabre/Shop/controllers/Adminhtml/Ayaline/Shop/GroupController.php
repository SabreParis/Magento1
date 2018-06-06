<?php

require_once Mage::getModuleDir('controllers', 'Ayaline_Shop') . DS . 'Adminhtml' . DS . 'Ayaline' . DS . 'Shop' . DS . 'GroupController.php';

class Sabre_Shop_Adminhtml_Ayaline_Shop_GroupController extends Ayaline_Shop_Adminhtml_Ayaline_Shop_GroupController
{

    const PICTO_PATH_FOLDER = 'shops/group';

    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        try {
            if ($data) {
                $redirectBack = $this->getRequest()->getParam('back', false);
                $this->_initGroup('group_id');

                /* @var $group Ayaline_Shop_Model_Shop_Group */
                $group = Mage::registry('current_ayaline_shop_group');
                $group->setData($data);
                $group->save();

                if (isset ($data['icon']['delete'])) {
                    $group->setIcon('');
                    $group->save();
                } elseif ($_FILES['icon']['name'] != '') {
                    $group->upload();
                    $group->save();
                } else {
                    $group->setIcon($data['old_icon']);
                    $group->save();
                }

                //Save marker img
                if (isset ($data['marker']['delete'])) {
                    $group->setMarker('');
                    $group->save();
                } elseif ($_FILES['marker']['name'] != '') {
                    $group->upload('marker');
                    $group->save();
                } else {
                    if (isset ($data['marker'])) {
                        $group->setMarker($data['old_marker']);
                        $group->save();
                    }
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('ayalineshop')->__('The group has been saved.')
                );
            }
            if ($redirectBack) {
                $this->_redirect('*/*/edit', array(
                    'id' => $group->getId(),
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
}