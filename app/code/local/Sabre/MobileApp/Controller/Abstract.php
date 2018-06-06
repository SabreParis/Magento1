<?php

/**
 * created: 2016
 *
 * @category  XXXXXXX
 * @package   Ayaline
 * @author    aYaline Magento <support.magento-shop@ayaline.com>
 * @copyright 2016 - aYaline Magento
 * @license   aYaline - http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 * @link      http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
abstract class Sabre_MobileApp_Controller_Abstract extends Mage_Core_Controller_Front_Action
{

    protected $_responseStatus = true;

    protected $_responseData = [
        'success' => [
            'page'  => 0,
            'limit' => 0,
            'count' => 0,
            'total' => 0,
            'data'  => [],
        ],
        'error'   => [
            'error' => [
                'message' => '',
            ],
        ],
    ];

    public function preDispatch()
    {
        parent::preDispatch();

        // add authentication

        return $this;
    }

    public function postDispatch()
    {
        $response = $this->_responseStatus ? $this->_responseData['success'] : $this->_responseData['error'];

        Mage::helper('sabre_configuration/ajax')->sendJsonResponse(
            $this,
            $response,
            ($this->_responseStatus ? 200 : 400)
        );

        return parent::postDispatch();
    }

    /**
     * @return Mage_Core_Model_Store
     */
    protected function _getStore()
    {
        $store = $this->getRequest()->getQuery('store', 'sabre_fr_french');

        return Mage::app()->getStore($store);
    }

    /**
     * @return int
     */
    protected function _getPageSize()
    {
        $pageSize = $this->getRequest()->getQuery(
            Mage_Api2_Model_Request::QUERY_PARAM_PAGE_SIZE,
            Mage_Api2_Model_Resource::PAGE_SIZE_DEFAULT
        );

        if ($pageSize != abs($pageSize) || $pageSize > Mage_Api2_Model_Resource::PAGE_SIZE_MAX) {
            $pageSize = Mage_Api2_Model_Resource::PAGE_SIZE_DEFAULT;
        }

        return $pageSize;
    }

    /**
     * @return int
     */
    protected function _getPageNumber()
    {
        $pageNumber = $this->getRequest()->getQuery(Mage_Api2_Model_Request::QUERY_PARAM_PAGE_NUM, 0);
        if ($pageNumber != abs($pageNumber)) {
            $pageNumber = 0;
        }

        return $pageNumber;
    }

}
