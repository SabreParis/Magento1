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
class Ayaline_EnhancedAdmin_Block_Adminhtml_Cache extends Mage_Adminhtml_Block_Cache
{

    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->_controller = 'adminhtml_cache';
        $this->_blockGroup = 'ayaline_enhancedadmin';
    }

}
