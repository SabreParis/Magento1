<?php

/**
 * created : 2014
 *
 * @category  Ayaline
 * @package   Ayaline_EnhancedAdmin
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_EnhancedAdmin_Block_Adminhtml_Cache_Grid extends Mage_Adminhtml_Block_Cache_Grid
{

    protected function _prepareColumns()
    {
        $this->addColumnAfter('action', array(
            'header'    => Mage::helper('adminhtml')->__('Action'),
            'width'     => '100',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('adminhtml')->__('Refresh'),
                    'url'     => array('base' => 'adminhtml/ayaline_cache/refresh'),
                    'field'   => 'type',
                ),
            ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ), 'status');

        return parent::_prepareColumns();
    }

}
