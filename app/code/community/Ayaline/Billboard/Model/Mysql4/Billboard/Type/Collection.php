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
class Ayaline_Billboard_Model_Mysql4_Billboard_Type_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    protected $_eventPrefix = 'ayalinebillboard_billboard_type_collection';
    protected $_eventObject = 'billboard_type_collection';

    protected function _construct()
    {
        $this->_init('ayalinebillboard/billboard_type');
    }

    public function addBillboardFilter($billboardId)
    {
        $this->getSelect()
             ->joinInner(
                 array('abbti' => $this->getTable('ayalinebillboard/billboard_type_index')),
                 'main_table.type_id = abbti.type_id',
                 array()
             )
             ->where('abbti.billboard_id = ?', $billboardId);

        return $this;
    }


}