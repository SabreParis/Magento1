<?php

/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Sabre_Dataflow
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Dataflow_Model_Resource_Helper_Mysql4 extends Mage_Core_Model_Resource_Helper_Mysql4
{

    /**
     * 
     * @param int $customerId
     * @return int
     */
    public function getCustomerOrdersCount($customerId)
    {
        $orderTable = Mage::getSingleton('core/resource')->getTableName('sales/order');

        /* @var $select Varien_Db_Select */
        $sql = $this->_getReadAdapter()
            ->select()
            ->from($orderTable, 'count(1)')
            ->where('customer_id=?', $customerId)
        ;

        $result = $this->_getReadAdapter()->fetchOne($sql);

        return $result ? (int) $result : 0;
    }
}
