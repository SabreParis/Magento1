<?php

/**
 * Created by PhpStorm.
 * User: ayoub
 * Date: 15/04/2016
 * Time: 08:33
 */
class Sabre_MobileApp_CategoryController extends Sabre_MobileApp_Controller_Abstract
{

    public function indexAction()
    {
        try {
            /** @var Mage_Catalog_Model_Resource_Category_Collection $collection */
            $collection = Mage::getResourceModel('catalog/category_collection');

            $collection->addAttributeToSelect('*')
                ->addAttributeToFilter(
                    'is_active',
                    array('eq' => 1)
                );


            $store = $this->_getStore();


            $collection->addAttributeToFilter('path',
                array('like'=>'1/'.$store->getRootCategoryId().'/%'));


            $collection->setStore($store);

            $collection->setPage($this->_getPageNumber(), $this->_getPageSize());
            if ($date = $this->_getDate()) {
                $collection->addAttributeToFilter(
                    'updated_at',
                    [
                        'gteq' => $date
                    ]
                );
            }


            $this->_responseData['success']['page'] = $collection->getCurPage();
            $this->_responseData['success']['limit'] = $collection->getPageSize();

            $this->_responseData['success']['total'] = $collection->getConnection()->fetchOne($collection->getSelectCountSql());
            $this->_responseData['success']['count'] = $collection->count();

            $this->_responseData['success']['data'] = $collection->toArray();
        } catch (Exception $e) {

            $this->_responseStatus = false;
            $this->_responseData['error']['error']['message'] = 'An error occurred';
        }

    }

    protected function _getDate()
    {
        $date = $this->getRequest()->getQuery('date', false);
        if ($date) {
            try {
                $date = DateTime::createFromFormat(Varien_Date::DATE_PHP_FORMAT, $date);
                if (!$date) {
                    throw new Exception('no date');
                }
            } catch (Exception $e) {
                $date = new DateTime('now');
                $date->sub(new DateInterval('P1W')); // last week
            }
            $date->setTime(0, 0, 0);

            return $date->format(Varien_Date::DATETIME_PHP_FORMAT);
        }

        return false;
    }
}