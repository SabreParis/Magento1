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
class Ayaline_GoogleUniversalAnalytics_Model_System_Config
{

    const XML_PATH_AYALINE_GUA_GENERAL_ENABLED = 'ayaline_gua/general/enabled';
    const XML_PATH_AYALINE_GUA_GENERAL_WEB_PROPERTY_ID = 'ayaline_gua/general/web_property_id';
    const XML_PATH_AYALINE_GUA_GENERAL_USE_ALTERNATIVE_ASYNCHRONOUS_SNIPPET = 'ayaline_gua/general/use_alternative_asynchronous_snippet';
    const XML_PATH_AYALINE_GUA_GENERAL_ANONYMIZE_IP = 'ayaline_gua/general/anonymize_ip';
    const XML_PATH_AYALINE_GUA_GENERAL_FORCE_SSL = 'ayaline_gua/general/force_ssl';
    const XML_PATH_AYALINE_GUA_GENERAL_DEBUG = 'ayaline_gua/general/debug';
    const XML_PATH_AYALINE_GUA_GENERAL_DEBUG_TRACE = 'ayaline_gua/general/debug_trace';

    const XML_PATH_AYALINE_GUA_CREATE_NODE = 'sections/ayaline_gua/groups/create/fields';
    const XML_PATH_AYALINE_GUA_CREATE_CONFIG = 'ayaline_gua/create/';

    const XML_PATH_AYALINE_GUA_USER_USER_ID = 'ayaline_gua/user/user_id';

    const XML_PATH_AYALINE_GUA_ECOMMERCE_ENABLE_CATEGORY = 'ayaline_gua/ecommerce/enable_category';

    const XML_PATH_AYALINE_GUA_ENABLED_ONEPAGE_TRACKING = 'ayaline_gua/onepage_tracking/enabled_onepage';
    const XML_PATH_AYALINE_GUA_ONEPAGE_TRACKING_LABEL_STEP = 'ayaline_gua/onepage_tracking_step/opc-';

    protected $_createConfig = null;

    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_GUA_GENERAL_ENABLED);
    }

    public function getWebPropertyId()
    {
        return Mage::getStoreConfig(self::XML_PATH_AYALINE_GUA_GENERAL_WEB_PROPERTY_ID);
    }

    public function canUseAlternativeAsynchronousSnippet()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_GUA_GENERAL_USE_ALTERNATIVE_ASYNCHRONOUS_SNIPPET);
    }

    public function anonymizeIp()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_GUA_GENERAL_ANONYMIZE_IP);
    }

    public function forceSsl()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_GUA_GENERAL_FORCE_SSL);
    }

    public function debug()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_GUA_GENERAL_DEBUG);
    }

    public function debugTrace()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_GUA_GENERAL_DEBUG_TRACE);
    }


    public function getCreateTrackerConfig()
    {
        if ($this->_createConfig === null) {
            $this->_createConfig = array();
            $knownKeys = array();
            $systemXml = Mage::getConfig()->loadModulesConfiguration('system.xml')->applyExtends();
            $config = $systemXml->getNode(self::XML_PATH_AYALINE_GUA_CREATE_NODE);

            /**
             * @var string                         $_configPath
             * @var Mage_Core_Model_Config_Element $_config
             */
            foreach ($config->children() as $_configPath => $_config) {
                preg_match('#([a-z_]+)_(heading|customize|value)#', $_configPath, $_matches);
                if (count($_matches) === 3) {
                    if (array_key_exists($_matches[1], $knownKeys)) {
                        continue;
                    }

                    $knownKeys[$_matches[1]] = $_matches[1];

                    $useBool = ($systemXml->getNode(self::XML_PATH_AYALINE_GUA_CREATE_NODE . "/{$_matches[1]}_value")->source_model->__toString() === 'adminhtml/system_config_source_yesno') ? true : false;

                    if (Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_GUA_CREATE_CONFIG . "{$_matches[1]}_customize")) {
                        $this->_createConfig[lcfirst(uc_words($_matches[1], ''))] = $useBool ?
                            Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_GUA_CREATE_CONFIG . "{$_matches[1]}_value") :
                            Mage::getStoreConfig(self::XML_PATH_AYALINE_GUA_CREATE_CONFIG . "{$_matches[1]}_value");
                    }
                }
            }

            if (!count($this->_createConfig)) {
                $this->_createConfig = false;
            }
        }

        return $this->_createConfig;
    }

    public function canUseUserId()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_GUA_USER_USER_ID);
    }

    public function useCategoryOnOrderItem()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_GUA_ECOMMERCE_ENABLE_CATEGORY);
    }

    /**
     * @return bool
     */
    public function onepageTrackingIsActive()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_AYALINE_GUA_ENABLED_ONEPAGE_TRACKING);
    }

    public function getOnepageTrackingSteps()
    {
        $onePageStepsLabel = [];
        $steps = Mage::getModel('ayaline_gua/system_config_clone_step_label')->getSteps();

        foreach ($steps as $step) {
            $label = Mage::getStoreConfig(self::XML_PATH_AYALINE_GUA_ONEPAGE_TRACKING_LABEL_STEP . $step.'_label');
            $onePageStepsLabel[$step]= empty($label) ? str_replace('_', ' ', $step) : $label;
        }

        return Mage::helper('core')->jsonEncode($onePageStepsLabel);
    }

} 