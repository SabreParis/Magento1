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
class Ayaline_Shop_Model_Mysql4_Shop_Postcode extends Mage_Core_Model_Mysql4_Abstract
{

    protected function _construct()
    {
        $this->_init('ayalineshop/postcodes', 'postcodes_id');
    }

    public function addShopPostcode($cusno, $postcodes)
    {
        $read = $this->_getReadAdapter();
        $select = $read->select()
                       ->from($this->getTable('ayalineshop/shop'), 'shop_id')
                       ->where('cusno = ?', $cusno);
        $shopId = $read->fetchOne($select);

        if (!is_array($postcodes)) {
            $postcodes = array($postcodes);
        }

        if ($shopId) {
            foreach ($postcodes as $_postcode) {
                if (!$this->_getRow($shopId, $_postcode)) {
                    $this->_getWriteAdapter()->insert($this->getMainTable(), array('shop_id'  => $shopId,
                                                                                   'postcode' => $_postcode
                        ));
                }
            }
        }

    }

    protected function _getRow($shopId, $postcode)
    {
        $read = $this->_getReadAdapter();
        $select = $read->select()
                       ->from($this->getMainTable())
                       ->where('shop_id = ?', $shopId)
                       ->where('postcode = ?', $postcode);

        return $read->fetchAll($select);
    }

}
