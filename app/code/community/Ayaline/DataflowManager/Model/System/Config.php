<?php

/**
 * created : 2014
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_DataflowManager_Model_System_Config
{
    // ACL
    const IS_ALLOWED_DATAFLOW_MANAGEMENT = 'system/ayaline_enhancedadmin/dataflow_management/';


    const DEFAULT_EMAIL_GROUP = 'email';

    // CONFIG
    const XML_PATH_CONFIG_X_EMAIL_TEMPLATE = 'ayaline_dataflowmanager/%s/email_template';
    const XML_PATH_CONFIG_X_SENDER_NAME = 'ayaline_dataflowmanager/%s/sender_name';
    const XML_PATH_CONFIG_X_SENDER_EMAIL = 'ayaline_dataflowmanager/%s/sender_email';
    const XML_PATH_CONFIG_X_RECIPIENT_NAME = 'ayaline_dataflowmanager/%s/recipient_name';
    const XML_PATH_CONFIG_X_RECIPIENT_EMAIL = 'ayaline_dataflowmanager/%s/recipient_email';
    const XML_PATH_CONFIG_X_RECIPIENT_CC_EMAIL = 'ayaline_dataflowmanager/%s/recipient_cc_email';

    const XML_PATH_CONFIG_LOG_RELATIVE_PATH = 'ayaline_dataflowmanager/log/relative_path';
    const XML_PATH_CONFIG_LOG_PATH = 'ayaline_dataflowmanager/log/path';

    const XML_PATH_CONFIG_X_BASE_PATH = 'ayaline_dataflowmanager/%s/base_path';

    const XML_PATH_CONFIG_IMPORT_X_IMAGE_SOURCE = 'ayaline_dataflowmanager/import_%s/image_source';
    const XML_PATH_CONFIG_IMPORT_X_IMAGE_LOCAL_PATH = 'ayaline_dataflowmanager/import_%s/image_source_local_path';
    const XML_PATH_CONFIG_IMPORT_X_IMAGE_HTTP_URL = 'ayaline_dataflowmanager/import_%s/image_source_http_url';

    protected function _getPath($format, $args)
    {
        return sprintf($format, $args);
    }

    #####################
    #####    ACL    #####
    #####################

    /**
     * @param string $code
     * @return bool
     */
    public function dataflowManagementIsAllowed($code)
    {
        return Mage::getSingleton('admin/session')->isAllowed(self::IS_ALLOWED_DATAFLOW_MANAGEMENT . $code);
    }

    #######################
    #####    Email    #####
    #######################

    /**
     * @param string $emailConfig
     * @return mixed
     */
    public function getEmailTemplate($emailConfig = self::DEFAULT_EMAIL_GROUP)
    {
        if ($emailTemplate = Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_EMAIL_TEMPLATE, $emailConfig))) {
            return $emailTemplate;
        }

        return Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_EMAIL_TEMPLATE, self::DEFAULT_EMAIL_GROUP));
    }

    /**
     * @param string $emailConfig
     * @return array
     */
    public function getSenderData($emailConfig = self::DEFAULT_EMAIL_GROUP)
    {
        $senderName = Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_SENDER_NAME, $emailConfig));
        $senderEmail = Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_SENDER_EMAIL, $emailConfig));

        return array(
            'name'  => $senderName ? $senderName : Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_SENDER_NAME, self::DEFAULT_EMAIL_GROUP)),
            'email' => $senderEmail ? $senderEmail : Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_SENDER_EMAIL, self::DEFAULT_EMAIL_GROUP)),
        );
    }

    /**
     * @param string $emailConfig
     * @return array
     */
    public function getRecipientsData($emailConfig = self::DEFAULT_EMAIL_GROUP)
    {
        $recipientName = Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_RECIPIENT_NAME, $emailConfig));
        $recipientEmail = Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_RECIPIENT_EMAIL, $emailConfig));

        $data = array(
            'name'     => $recipientName ? $recipientName : Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_RECIPIENT_NAME, self::DEFAULT_EMAIL_GROUP)),
            'email'    => $recipientEmail ? $recipientEmail : Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_RECIPIENT_EMAIL, self::DEFAULT_EMAIL_GROUP)),
            'cc_email' => false,
        );

        $ccEmail = Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_RECIPIENT_CC_EMAIL, $emailConfig));
        $ccEmail = $ccEmail ? $ccEmail : Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_RECIPIENT_CC_EMAIL, self::DEFAULT_EMAIL_GROUP));
        if (!empty($ccEmail)) {
            $ccEmail = explode(',', $ccEmail);
            $data['cc_email'] = array_map('trim', $ccEmail);
        }

        return $data;
    }

    #####################
    #####    Log    #####
    #####################

    /**
     * @return bool
     */
    public function useRelativePath()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_CONFIG_LOG_RELATIVE_PATH);
    }

    /**
     * @return string
     */
    public function getLogPath()
    {
        $logPath = Mage::getStoreConfig(self::XML_PATH_CONFIG_LOG_PATH);
        $logPath = rtrim($logPath, DS);
        if (strpos($logPath, DS) !== 0) {
            $logPath = DS . $logPath;
        }

        return $logPath;
    }

    ######################
    #####    Path    #####
    ######################

    /**
     * @param string $type
     * @return string
     */
    public function getBasePath($type)
    {
        $basePath = Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_X_BASE_PATH, $type));
        trim($basePath, DS);

        return DS . $basePath . DS;
    }


    ########################
    #####    Import    #####
    ########################

    /**
     * @param string $type
     * @return string (@see Ayaline_DataflowManager_Model_System_Config_Source_ImageSource)
     */
    public function getImportImageSource($type)
    {
        return Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_IMPORT_X_IMAGE_SOURCE, $type));
    }

    /**
     * @param string $type
     * @return string
     */
    public function getImportImageLocalPath($type)
    {
        $localPath = Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_IMPORT_X_IMAGE_LOCAL_PATH, $type));
        $localPath = trim($localPath, DS);

        return DS . $localPath . DS;
    }

    /**
     * @param string $type
     * @return string
     */
    public function getImportImageHttpUrl($type)
    {
        $url = Mage::getStoreConfig($this->_getPath(self::XML_PATH_CONFIG_IMPORT_X_IMAGE_HTTP_URL, $type));
        $url = rtrim($url, '/');

        return $url;
    }

} 