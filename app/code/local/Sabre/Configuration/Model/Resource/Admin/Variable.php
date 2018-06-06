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
class Sabre_Configuration_Model_Resource_Admin_Variable extends Mage_Admin_Model_Resource_Variable
{

    /**
     * @param $path string
     * @return int
     */
    public function isPathAllowed($path)
    {
        $select = $this->_getReadAdapter()
                       ->select()
                       ->from($this->getMainTable(), 'COUNT(1)')
                       ->where('variable_name = ?', $path)
                       ->where('is_allowed = 1');

        return $this->_getReadAdapter()->fetchOne($select);
    }

}
