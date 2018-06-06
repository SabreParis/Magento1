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
class Ayaline_EnhancedAdmin_Model_Resource_Core_Resource extends Mage_Core_Model_Resource_Resource
{

    /**
     * Set module version into DB
     *
     * @param string $resName
     * @param string $version
     * @return int
     */
    public function setDbVersion($resName, $version)
    {
        $parentRes = parent::setDbVersion($resName, $version);
        // I want to add a dispatch event but it's doesn't works because Magento is not completely initialized
        Mage::getResourceSingleton('ayaline_enhancedadmin/resourceSetup')->setIsApplied($resName, $version, Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_SQL);

        return $parentRes;
    }

    /**
     * Specify resource data version
     *
     * @param string $resName
     * @param string $version
     * @return Mage_Core_Model_Resource_Resource
     */
    public function setDataVersion($resName, $version)
    {
        $parentRes = parent::setDataVersion($resName, $version);
        // I want to add a dispatch event but it's doesn't works because Magento is not completely initialized
        Mage::getResourceSingleton('ayaline_enhancedadmin/resourceSetup')->setIsApplied($resName, $version, Ayaline_EnhancedAdmin_Model_System_Source_Module_SetupType::SETUP_TYPE_DATA);

        return $parentRes;
    }

}
