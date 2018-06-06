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

require_once Mage::getModuleDir('controllers', 'Mage_Adminhtml') . DS . 'CacheController.php';

class Ayaline_EnhancedAdmin_Adminhtml_Ayaline_CacheController extends Mage_Adminhtml_CacheController
{

    public function refreshAction()
    {
        if ($type = $this->getRequest()->getParam('type')) {
            Mage::app()->getCacheInstance()->cleanType($type);
            Mage::dispatchEvent('adminhtml_cache_refresh_type', array('type' => $type));
            $this->_getSession()->addSuccess($this->__("%s cache refreshed.", uc_words($type, ' ')));
        }

        $this->_redirect('*/cache/');
    }

}
