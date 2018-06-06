<?php

/**
 * created : 2013
 *
 * @category  Ayaline
 * @package   Ayaline_EnhancedAdmin
 * @author    aYaline
 * @copyright Ayaline - 2013 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_EnhancedAdmin_Block_Adminhtml_Setup_View_File extends Mage_Adminhtml_Block_Template
{

    /**
     * @return Ayaline_EnhancedAdmin_Model_Module
     */
    public function getModule()
    {
        return Mage::registry('ayaline_enhancedadmin_module');
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return Mage::registry('ayaline_enhancedadmin_files_content');
    }

} 