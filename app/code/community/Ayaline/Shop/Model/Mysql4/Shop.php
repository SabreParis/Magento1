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
 * Shop resource model
 *
 */
class Ayaline_Shop_Model_Mysql4_Shop extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('ayalineshop/shop', 'shop_id');
    }

    public function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        //Date du contrat
        if (!$object->getContractStartDate()) {
            $object->setContractStartDate(new Zend_Db_Expr('NULL'));
        }
        if ($object->getContractStartDate() instanceof Zend_Date) {
            $object->setContractStartDate($object->getContractEndDate()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
        }

        if (!$object->getContractEndDate()) {
            $object->setContractEndDate(new Zend_Db_Expr('NULL'));
        } else {
            if ($object->getContractEndDate() instanceof Zend_Date) {
                $object->setContractEndDate($object->getContractEndDate()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
            }
        }


        //Activation
        if ($object->getIsActive() != $object->getOldIsActive() && $object->getIsActive() == true) {
            $object->setUpdatedActiveAt(Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
        }
        parent::_beforeSave($object);
    }

    public function exists($idShop = null)
    {
        try {
            $select = $this->_getReadAdapter()
                           ->select()
                           ->from(array('shop' => $this->getMainTable()), 'shop_id');

            if ($idShop) {
                $select->where('shop.shop_id != ?', $idShop);
            }

            $data = $this->_getReadAdapter()->fetchCol($select);

        } catch (Exception $e) {
            Mage::logException($e);
        }
        $count = 0;
        if ($data && is_array($data)) {
            $count += sizeof($data);
        }

        return $count > 0 ? true : false;
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('shop_id = ?', $object->getId());

        //Zone de chalandise
        $this->_getWriteAdapter()->delete($this->getTable('ayalineshop/postcodes'), $condition);

        //Horaires
        $this->_getWriteAdapter()->delete($this->getTable('ayalineshop/schedules'), $condition);

        //Horaires spéciaux
        $this->_getWriteAdapter()->delete($this->getTable('ayalineshop/special_schedules'), $condition);
        parent::_beforeDelete();
    }

    public function saveStoreRelations($shop)
    {
        $stores = $shop->getStores();
        try {
            // Delete the old relations
            $condition = $this->_getWriteAdapter()->quoteInto('shop_id = ?', $shop->getShopId());
            $this->_getWriteAdapter()->delete($this->getTable('ayalineshop/shop_store'), $condition);

            if (in_array(0, $stores)) {
                // All stores
                $this->_getWriteAdapter()->insert($this->getTable('ayalineshop/shop_store'), array('shop_id'  => $shop->getShopId(),
                                                                                                   'store_id' => 0
                    ));
            } else {
                // Specific stores
                foreach ($stores as $store) {
                    $this->_getWriteAdapter()->insert($this->getTable('ayalineshop/shop_store'), array('shop_id'  => $shop->getShopId(),
                                                                                                       'store_id' => $store
                        ));
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    public function prepareStores($shop)
    {
        $select = $this->_getReadAdapter()
                       ->select()
                       ->from(array('shop_store' => $this->getTable('ayalineshop/shop_store')), 'store_id');

        if ($shop->getShopId()) {
            $select->where('shop_store.shop_id = ?', $shop->getShopId());
        }
        $data = $this->_getReadAdapter()->fetchCol($select);
        $shop->setStores($data);

        return $shop;
    }
}
