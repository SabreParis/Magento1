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
//class Ayaline_EnhancedAdmin_Model_System_Template_Setup extends Mage_Catalog_Model_Resource_Setup
class {{var className}} extends {{var extendsClassName}}
{

    /**
     * Include file
     */
    public
    function includeFilename($filename)
    {
        include $filename;
    }

}
