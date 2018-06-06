<?php

/**
 * created: 2014
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

class Ayaline_DataflowManager_Block_Adminhtml_DataflowManager extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct(array $args = array())
    {
        $this->_controller = 'adminhtml_dataflowManager';
        $this->_blockGroup = 'ayaline_dataflowmanager';
        $this->_headerText = $this->__('Dataflow Manager');
        parent::__construct($args);
        $this->_removeButton('add');
    }

} 