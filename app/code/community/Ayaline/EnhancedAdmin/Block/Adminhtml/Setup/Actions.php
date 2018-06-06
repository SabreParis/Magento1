<?php

/**
 * created : 2014
 *
 * @category  Ayaline
 * @package   Ayaline_EnhancedAdmin
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_EnhancedAdmin_Block_Adminhtml_Setup_Actions extends Mage_Adminhtml_Block_Widget
{

    protected $_confirmMessage = '';

    protected function _construct()
    {
        parent::_construct();
        $this->_confirmMessage = addslashes($this->escapeHtml($this->__('Are you sure you want to proceed?')));
    }

    protected function _getApplyUpdatesButton($type = null)
    {
        $params = array('_current' => true);
        if (!is_null($type)) {
            $params['type'] = $type;
        }

        $action = ($this->getModule()) ? $this->getUrl('*/*/applyModuleUpdates', $params) : $this->getUrl('*/*/applyAllUpdates', $params);

        return $this->getButtonHtml(
            $this->__('Apply Updates'),
            "confirmSetLocation('{$this->_confirmMessage}', '{$action}');",
            'add',
            "default_apply_updates_{$type}"
        );
    }

    protected function _getApplyUnAppliedUpdatesButton($type = null)
    {
        $params = array('_current' => true);
        if (!is_null($type)) {
            $params['type'] = $type;
        }

        if ($this->getModule()) {
            $params['id'] = $this->getModule()->getId();
        }

        return $this->getButtonHtml(
            $this->__('Apply Updates'),
            "confirmSetLocation('{$this->_confirmMessage}', '{$this->getUrl('*/*/applyUnAppliedUpdates', $params)}');",
            'add',
            "custom_apply_updates_{$type}"
        );
    }

    protected function _getFlushSetupClassButton()
    {
        return $this->getButtonHtml(
            $this->__('Flush Setup Class Cache'),
            "confirmSetLocation('{$this->_confirmMessage}', '{$this->getUrl('*/*/flushClass')}');",
            'delete',
            'setup_class_cache'
        );
    }

    protected function _getCheckSetupsButton()
    {
        if ($this->getModule()) {
            return $this->getButtonHtml(
                $this->__('Check Module Setups'),
                "confirmSetLocation('{$this->_confirmMessage}', '{$this->getUrl('*/*/checkSetups', array('id' => $this->getModule()->getId()))}');",
                'show-hide',
                'check_setups'
            );
        }

        return $this->getButtonHtml(
            $this->__('Check Setups'),
            "confirmSetLocation('{$this->_confirmMessage}', '{$this->getUrl('*/*/checkSetups')}');",
            'show-hide',
            'check_setups'
        );
    }

    /**
     * @return Ayaline_EnhancedAdmin_Model_Module
     */
    public function getModule()
    {
        return Mage::registry('ayaline_enhancedadmin_module');
    }

    public function getActions()
    {
        $actions = array();

        if (Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('default_apply')) {
            $actions['default_apply'] = array(
                'name'        => $this->__('Apply updates'),
                'description' => ($this->getModule() ? $this->__('Only apply setup to version %s', $this->getModule()->getVersion()) : $this->__('Only apply setup to last version')),
                'actions'     => array(
                    array(
                        'label'  => $this->__('Apply Updates'),
                        'button' => $this->_getApplyUpdatesButton(),
                        'note'   => '',
                    ),
                    array(
                        'label'  => $this->__('Apply Sql Updates'),
                        'button' => $this->_getApplyUpdatesButton(Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_SQL),
                        'note'   => '',
                    ),
                    array(
                        'label'  => $this->__('Apply Data Updates'),
                        'button' => $this->_getApplyUpdatesButton(Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_DATA),
                        'note'   => '',
                    ),
                ),
            );
        }

        if (Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('custom_apply')) {
            $actions['custom_apply'] = array(
                'name'        => $this->__('Apply un-applied updates'),
                'description' => $this->__('Only apply un-applied updates (does not use config.xml version)'),
                'actions'     => array(
                    array(
                        'label'  => $this->__('Apply Updates'),
                        'button' => $this->_getApplyUnAppliedUpdatesButton(),
                        'note'   => '',
                    ),
                    array(
                        'label'  => $this->__('Apply Sql Updates'),
                        'button' => $this->_getApplyUnAppliedUpdatesButton(Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_SQL),
                        'note'   => '',
                    ),
                    array(
                        'label'  => $this->__('Apply Data Updates'),
                        'button' => $this->_getApplyUnAppliedUpdatesButton(Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_DATA),
                        'note'   => '',
                    ),
                ),
            );
        }

        if (Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('check_setups')) {
            $actions['check_setups'] = array(
                'name'        => $this->__('Check Setups'),
                'description' => $this->__('Check if new setup files exists'),
                'actions'     => array(
                    array(
                        'label'  => $this->__('Check Setups'),
                        'button' => $this->_getCheckSetupsButton(),
                        'note'   => '',
                    ),
                ),
            );
        }

        if (Mage::getSingleton('ayaline_enhancedadmin/system_config')->setupManagementIsAllowed('setup_class_cache')) {
            $actions['setup_class_cache'] = array(
                'name'        => $this->__('Flush Setup Class Cache'),
                'description' => $this->__('Flush setup class only if you alter template'),
                'actions'     => array(
                    array(
                        'label'  => $this->__('Flush Setup Class Cache'),
                        'button' => $this->_getFlushSetupClassButton(),
                        'note'   => '',
                    ),
                ),
            );
        }

        return $actions;
    }

} 