<?php
/**
 * created : 10/08/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 *
 * @package Ayaline_Billboard
 */
class Ayaline_Billboard_Adminhtml_Ayaline_Billboard_WidgetController extends Mage_Adminhtml_Controller_Action
{

    protected function _construct()
    {
        $this->setUsedModuleName('Ayaline_Billboard');
    }

    public function chooserAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $pagesGrid = $this->getLayout()->createBlock('ayalinebillboard/adminhtml_widget_billboard', '', array('id' => $uniqId));
        $this->getResponse()->setBody($pagesGrid->toHtml());
    }

}