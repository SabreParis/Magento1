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
class Ayaline_EnhancedAdmin_Model_System_Config
{

    //  ACL
    const IS_ALLOWED_SETUP_MANAGEMENT = 'system/ayaline_enhancedadmin/setup_management/';


    //  Configuration


    #####################
    #####    ACL    #####
    #####################

    public function setupManagementIsAllowed($code)
    {
        return Mage::getSingleton('admin/session')->isAllowed(Ayaline_EnhancedAdmin_Model_System_Config::IS_ALLOWED_SETUP_MANAGEMENT . $code);
    }

}
