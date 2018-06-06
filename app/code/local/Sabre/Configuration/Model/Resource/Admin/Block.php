<?php

/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Configuration_Model_Resource_Admin_Block extends Mage_Admin_Model_Resource_Block
{

    /**
     * @param $type string
     * @return int
     */
    public function isTypeAllowed($type)
    {
        $select = $this->_getReadAdapter()
                       ->select()
                       ->from($this->getMainTable(), 'COUNT(1)')
                       ->where('block_name = ?', $type)
                       ->where('is_allowed = 1');

        return $this->_getReadAdapter()->fetchOne($select);
    }

}
