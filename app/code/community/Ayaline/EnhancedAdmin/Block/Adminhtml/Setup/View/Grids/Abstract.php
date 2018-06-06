<?php

/**
 * created : 2013
 *
 * @category  Ayaline
 * @package   Ayaline_EnhancedAdmin
 * @author    aYaline
 * @copyright Ayaline - 2013 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_EnhancedAdmin_Block_Adminhtml_Setup_View_Grids_Abstract extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->setUseAjax(true);
        $this->setDefaultSort('code');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
    }

    /**
     * @return Ayaline_EnhancedAdmin_Model_Module
     */
    protected function _getModule()
    {
        return Mage::registry('ayaline_enhancedadmin_module');
    }

    public function getRowUrl($row)
    {
        return "#";
    }

    public function getRowClickCallback()
    {
        return "(function() { return false; })";
    }

    public function getRowInitCallback()
    {
        return 'setSelectTypeStyle';
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

}
