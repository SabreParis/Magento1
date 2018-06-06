<?php

/**
 * created : 21/10/2015
 *
 * @category Ayaline
 * @package Ayaline_Billboard
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

class Sabre_Catalog_Model_Resource_Layer_Filter_Attribute extends Mage_Catalog_Model_Resource_Layer_Filter_Attribute
{

    /**
     * {@inheritdoc}
     *
     * aYaline: avoid "You cannot define a correlation name 'xxx_idx' more than once"
     */
    public function applyFilterToCollection($filter, $value)
    {
        $collection = $filter->getLayer()->getProductCollection();
        $attribute  = $filter->getAttributeModel();
        $connection = $this->_getReadAdapter();
        $tableAlias = $attribute->getAttributeCode() . '_idx';
        $conditions = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId()),
            $connection->quoteInto("{$tableAlias}.value = ?", $value)
        );

        $parts = $collection->getSelect()->getPart(Zend_Db_Select::FROM);
        if (array_key_exists($tableAlias, $parts)) {
            unset($parts[$tableAlias]);
            $collection->getSelect()->setPart(Zend_Db_Select::FROM, $parts);
        }

        $collection->getSelect()->join(
            array($tableAlias => $this->getMainTable()),
            implode(' AND ', $conditions),
            array()
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * aYaline: avoid "You cannot define a correlation name 'xxx_idx' more than once"
     */
    public function getCount($filter)
    {
        // clone select from collection with filters
        $select = clone $filter->getLayer()->getProductCollection()->getSelect();
        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        $connection = $this->_getReadAdapter();
        $attribute  = $filter->getAttributeModel();
        $tableAlias = sprintf('%s_idx', $attribute->getAttributeCode());
        $conditions = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
        );

        $parts = $select->getPart(Zend_Db_Select::FROM);
        if (array_key_exists($tableAlias, $parts)) {
            unset($parts[$tableAlias]);
            $select->setPart(Zend_Db_Select::FROM, $parts);
        }

        $select
            ->join(
                array($tableAlias => $this->getMainTable()),
                join(' AND ', $conditions),
                array('value', 'count' => new Zend_Db_Expr("COUNT({$tableAlias}.entity_id)")))
            ->group("{$tableAlias}.value");

        return $connection->fetchPairs($select);
    }

}