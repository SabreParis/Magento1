<?php
/**
 * created : 10/03/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Shop
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 * Shop controller
 *
 */
class Ayaline_Shop_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function viewAction()
    {

        $this->loadLayout();
        $idShop = $this->getRequest()->getParam('id');
        if ($idShop) {
            $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
            if ($breadcrumbsBlock) {
                $shop = Mage::getModel('ayalineshop/shop')->load($idShop);
                $breadcrumbsBlock->addCrumb('home', array(
                    'label' => Mage::helper('catalog')->__('Home'),
                    'title' => Mage::helper('catalog')->__('Go to Home Page'),
                    'link'  => Mage::getBaseUrl()

                ));

                $breadcrumbsBlock->addCrumb('ayaline_breadcrumb_list', array(
                    'label' => $this->__('Shops'),
                    'title' => $this->__('Shops'),
                    'link'  => Mage::getUrl('*/*')

                ));

                $breadcrumbsBlock->addCrumb('ayaline_breadcrumb_shop', array(
                    'label' => $shop->getTitle(),
                    'title' => $shop->getTitle()

                ));
            }
        }
        $this->renderLayout();

    }

    public function updateShopListAction()
    {
        try {
            $response = new Varien_Object();

            //Parameters
            $data = $this->getRequest()->getPost();
            $postcode = $data['postcode'];

            $resultShops = array();
            $shops = Mage::getModel('ayalineshop/shop')->getCollection()
                         ->addPostcodeFilter($postcode)
                         ->addIsActiveFilter()
                         ->addContractVadFilter(true)
                         ->addOrderBy(array('main_table.postcode', 'title', 'city'));

            $resultShops[] = array('id' => '-1', 'name' => $this->__('Please select a shop'));
            $resultShops[] = array('id' => '0', 'name' => $this->__('jeuxvideoandco.com'));
            foreach ($shops as $shop) {
                $name = substr($shop->getPostcode(), 0, 2) . ' - ' . $shop->getCity() . ' - ' . $shop->getTitle();
                $resultShops[] = array('id' => $shop->getId(), 'name' => $name);
            }

            //Response
            $response->setError(false);

            $response->setResult($resultShops);
        } catch (Exception $e) {
            Mage::logException($e);
        }

        //Send response
        $this->getResponse()->setBody($response->toJson());

    }
}
