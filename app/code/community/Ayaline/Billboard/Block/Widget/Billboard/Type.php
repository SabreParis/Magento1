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
class Ayaline_Billboard_Block_Widget_Billboard_Type extends Ayaline_Billboard_Block_Billboard implements Mage_Widget_Block_Interface
{

    public function getCacheKeyInfo()
    {
        $keyInfo = parent::getCacheKeyInfo();
        $keyInfo[] = $this->getType();

        return $keyInfo;
    }

    public function getType()
    {
        if (!$this->hasData('type_identifier')) {
            $this->setData('type_identifier', '');
        }

        return $this->getData('type_identifier');
    }

    /**
     * Retrieve billboards collection
     *
     * @return Ayaline_Billboard_Model_Mysql4_Billboard_Collection
     */
    public function getBillboards()
    {
        if (is_null($this->_billboards)) {
            /* @var $collection Ayaline_Billboard_Model_Mysql4_Billboard_Collection */
            $collection = Mage::getResourceModel('ayalinebillboard/billboard_collection');
            $collection
                ->addStatusFilter()//	only active billboards,
                ->addStoreFilter($this->_getStore())//	associate to this store,
                ->addBillboardTypeIdentifierFilter($this->getType())//	filter by billboard type
                ->addCustomerGroupFilter($this->_getCustomerGroup())//	associate to this customer
                ->orderByWidgetPosition()                                //	order by position
            ;

            if ($this->canFilterByDatetime()) {
                $collection->addDateTimeFilter();                        //	and visible now
            }

            $this->_billboards = $collection;
        }

        return $this->_billboards;
    }

}