<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Configuration controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Sabre_Dataflow_Adminhtml_System_ConfigController extends Mage_Adminhtml_Controller_Action
{

    /**
     * Export shipping table rates in csv format
     *
     */
    public function generateXsltParamsAction()
    {

        try {


            // Chargement de la template
            $templateFile = Mage::getModuleDir(null, "Sabre_Dataflow") . DS . "xslt" . DS . "sabre-params.tpl.xslt";
            if (!file_exists($templateFile)) {
                throw new Mage_Exception($this->__("No template file %s", $templateFile));
            }
            $xsltTemplate = file_get_contents($templateFile);

            // Définition des variables
            $variables = array();
            /* @var $website Mage_Core_Model_Website */
            $website = Mage::getModel("core/website")->load(Mage::getStoreConfig("ayaline_dataflowmanager/import_product/config_website_france_id"));
            if (!$website || !$website->getId()) {
                throw new Mage_Exception($this->__("No website defined"));
            }

            // Mapping locale / langue
            $varLanguageLocaleMapping = array();
            $tbLanguageLocaleMapping = array();
            $localeMapping = Mage::getStoreConfig("ayaline_dataflowmanager/import_product/config_mapping_locale_language");
            $localeMapping = unserialize($localeMapping);
            foreach ($localeMapping as $_item) {
                $varLanguageLocaleMapping[] = '<lang locale="'. $_item['locale'] .'">'. $_item['language'] .'</lang>';
                $tbLanguageLocaleMapping[$_item['locale']] = $_item['language'];
            }

            $categoriesMapping = Mage::getStoreConfig("ayaline_dataflowmanager/import_product/config_mapping_category");
            $categoriesMapping = unserialize($categoriesMapping);
            $varCatMapping1 = array();
            $varCatMapping2 = array();
            foreach ($categoriesMapping as $_item) {
                $varCatMapping1[] = "<category type='{$_item['sabre_category_code']}' magento_id='{$_item['magento_category']}' attribute_set='{$_item['magento_attribute_set']}' />";
                $varCatMapping2[] = "<article_attribute_code type='{$_item['sabre_category_code']}' code_article='{$_item['magento_article_attribute']}' code_model='{$_item['magento_model_attribute']}' />";
            }

            $websitesMapping = Mage::getStoreConfig("ayaline_dataflowmanager/import_product/config_mapping_website");
            $websitesMapping = unserialize($websitesMapping);
            $varWebsiteMapping = array();
            foreach ($websitesMapping as $_item) {
                $string = '<website magento="'. $_item['magento_website_code'] .'" erp="'. $_item['sabre_website_code'] .'">';
                $_website = Mage::getModel('core/website')->load($_item['magento_website_code'], 'code');
                foreach ($_website->getStores() as $store) {
                    $codeLocale = Mage::getStoreConfig('general/locale/code', $store);
                    $codeLanguage = $tbLanguageLocaleMapping[$codeLocale];
                    $string .= '<locale codeLocale="'. $codeLocale .'" codeLang="'. $codeLanguage .'" />';
                }
                $string .= '</website>';
                $varWebsiteMapping[] = $string;
            }

            $variables["tax_group_product"] = Mage::getStoreConfig("ayaline_dataflowmanager/import_product/config_tax_group_product");
            $variables["categories_mapping"] = implode("\n", $varCatMapping1);
            $variables["attribute_codes_mapping"] = implode("\n", $varCatMapping2);
            $variables["websites_mapping"] = implode("\n", $varWebsiteMapping);
            $variables["language_locale_mapping"] = implode("\n", $varLanguageLocaleMapping);

            // Filtre
            $filter = Mage::getModel('cms/template_filter');
            $filter->setVariables($variables);
            $xsltContent = $filter->filter($xsltTemplate);

            // Ecriture du fichier
            $destDir = Mage::getBaseDir("var") . DS . "xslt";
            if (!file_exists($destDir)) {
                $return = mkdir($destDir);
                if (!$return) {
                    throw new Mage_Exception($this->__("Could not create directory %s", $destDir));
                }
            }
            $fileDest = $destDir . DS . "sabre-params.xslt";
            $return = file_put_contents($fileDest, $xsltContent);
            if ($return === false) {
                throw new Mage_Exception($this->__("Could not write file %s", $fileDest));
            }

            // ***************************************************
            // Ecriture du fichier contenant la liste des websites
            // ***************************************************
            try {
                $websites = Mage::getModel("core/website")->getCollection();
                $xmlWebsites = new SimpleXMLElement("<websites></websites>");
                foreach ($websites as $_website) {
                    /* @var $_website Mage_Core_Model_Website */
                    $xmlWebsite = $xmlWebsites->addChild("website");
                    $xmlWebsite->addAttribute("code", $_website->getCode());
                    $xmlWebsite->addAttribute("currency", $_website->getBaseCurrencyCode());
                    $xmlWebsite->addAttribute("vat_included", Mage::getStoreConfig("tax/calculation/price_includes_tax", $_website->getDefaultStore()));
                    foreach ($_website->getStores() as $_store) {
                        /* @var $_store Mage_Core_Model_Store */
                        $xmlWebsite->addChild("locale", Mage::getStoreConfig("general/locale/code", $_store));
                    }
                }
                $fileDest = $destDir . DS . 'websites.xml';
                $xmlWebsites->asXML($fileDest);
            } catch (Exception $ane) {
                throw new Mage_Exception($this->__("Could not write file %s", $fileDest));
            }


        } catch (Mage_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectError($this->_getRefererUrl());
            return;
        }

        $message = Mage::getModel("core/message_success");
        $message->setCode($this->__("XSLT File has been generated with success"));
        $this->_getSession()->addMessage($message);

        $message2 = Mage::getModel("core/message_success");
        $message2->setCode($this->__("XML File with websites has been generated with success"));
        $this->_getSession()->addMessage($message2);

        $this->_redirectSuccess($this->_getRefererUrl());

    }

}
