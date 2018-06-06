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
class Ayaline_DataflowManager_Model_Core_Email_Template_Mailer extends Mage_Core_Model_Email_Template_Mailer
{

    protected $_attachments = array();

    /**
     * @param array $attachments (array of Zend_Mime_Part)
     * @return $this
     */
    public function setAttachment($attachments)
    {
        $this->_attachments = $attachments;

        return $this;
    }


    /**
     * Send all emails from email list
     * Add attachment logic
     *
     * @see self::$_emailInfos
     *
     * @return $this
     */
    public function send()
    {
        $emailTemplate = Mage::getModel('core/email_template');

        if (count($this->_attachments)) {
            foreach ($this->_attachments as $_attachment) {
                $emailTemplate->getMail()->addAttachment($_attachment);
            }
        }

        // Send all emails from corresponding list
        while (!empty($this->_emailInfos)) {
            $emailInfo = array_pop($this->_emailInfos);
            // Handle "Bcc" recipients of the current email
            $emailTemplate->addBcc($emailInfo->getBccEmails());
            // Set required design parameters and delegate email sending to Mage_Core_Model_Email_Template
            $emailTemplate->setDesignConfig(array(
                'area'  => 'frontend',
                'store' => $this->getStoreId(),
            ));
            $emailTemplate->sendTransactional(
                $this->getTemplateId(),
                $this->getSender(),
                $emailInfo->getToEmails(),
                $emailInfo->getToNames(),
                $this->getTemplateParams(),
                $this->getStoreId()
            );
        }

        return $this;
    }

} 