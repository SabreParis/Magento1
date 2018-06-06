<?php

/**
 * Created : 2015
 * 
 * @category Ayaline
 * @package Sabre_Adminhtml
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Adminhtml_Block_Sales_Order_View extends Mage_Adminhtml_Block_Sales_Order_View
{

    public function __construct()
    {
        parent::__construct();
        $this->_addButton('export', array(
            'label' => Mage::helper('sabre_adminhtml')->__('Launch export'),
            'onclick' => 'setLocation(\'' . $this->getExportOrderUrl() . '\')',
            ), -1);
    }

    public function getExportOrderUrl()
    {
        return $this->getUrl('*/*/exportOrder', array($this->_objectId => $this->getRequest()->getParam($this->_objectId)));
    }
}
