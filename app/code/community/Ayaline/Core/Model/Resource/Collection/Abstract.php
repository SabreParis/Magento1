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

class Ayaline_Core_Model_Resource_Collection_Abstract extends Varien_Data_Collection
{

    /**
     * Filter items
     *  only support neq, like and eq
     *
     * @return $this|Varien_Data_Collection
     */
    protected function _renderFilters()
    {
        if ($this->_isFiltersRendered) {
            return $this;
        }

        /** @var $_filter Varien_Object */
        foreach ($this->_filters as $_filter) {
            $toKeep = array();

            /** @var $_item Varien_Object */
            foreach ($this->_items as $_item) {

                if (is_object($_filter->getValue()) && method_exists($_filter->getValue(), '__toString')) {
                    $_value = $_filter->getValue()->__toString();
                } else {
                    $_value = $_filter->getValue();
                }

                $_value = trim($_value, "'");
                $_value = trim($_value, "%");

                switch ($_filter->getType()) {
                    case 'neq':
                        if ($_item->getData($_filter->getField()) != $_value) {
                            $toKeep[$_item->getId()] = $_item->getId();
                        }
                        break;
                    case 'like':
                        if (preg_match("#{$_value}#i", $_item->getData($_filter->getField()))) {
                            $toKeep[$_item->getId()] = $_item->getId();
                        }
                        break;
                    case 'eq':
                    default:
                        if ($_item->getData($_filter->getField()) == $_value) {
                            $toKeep[$_item->getId()] = $_item->getId();
                        }
                        break;
                }
            }
            $toRemove = array_diff(array_keys($this->_items), $toKeep);
            foreach ($toRemove as $_keyToRemove) {
                $this->removeItemByKey($_keyToRemove);
            }
        }

        $this->_isFiltersRendered = true;

        return $this;
    }

    /**
     * Render collection order
     *  only support one order
     *
     * @return $this|Varien_Data_Collection
     */
    protected function _renderOrders()
    {
        if (count($this->_orders) == 2) {
            $tmp = array();

            /** @var $_item Varien_Object */
            foreach ($this->_items as $_item) {
                $tmp[$_item->getId()] = $_item->getData($this->_orders['field']);
            }

            if ($this->_orders['direction'] == Varien_Data_Collection::SORT_ORDER_ASC) {
                asort($tmp);
            } else {
                arsort($tmp);
            }
            $items = $this->_items;
            $this->clear();
            foreach ($tmp as $_key => $_useless) {
                $this->addItem($items[$_key]);
            }

        }

        return $this;
    }

    /**
     * Set collection page and size
     *
     * @return $this|Varien_Data_Collection
     */
    protected function _renderLimit()
    {
        if ($this->getPageSize()) {
            $this->_totalRecords = count($this->_items);
            $from = $this->_curPage == 1 ? 0 : ($this->getPageSize() * ($this->_curPage - 1));

            $toKeep = array_slice($this->_items, $from, $this->getPageSize(), true);
            $toKeepKeys = array_keys($toKeep);

            /** @var $_item Varien_Object */
            foreach ($this->_items as $_item) {
                if (!in_array($_item->getId(), $toKeepKeys)) {
                    $this->removeItemByKey($_item->getId());
                }
            }
        }

        return $this;
    }

    /**
     * @param                   $field
     * @param null|string|array $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if (is_string($condition)) {
            return $this->addFilter($field, $condition, 'eq');
        } elseif (is_array($condition)) {
            $_value = reset($condition);

            return $this->addFilter($field, $_value, array_search($_value, $condition));
        }

        return $this;
    }

    /**
     * Support only one order
     *
     * @param string $field
     * @param string $direction
     * @return $this|Varien_Data_Collection
     */
    public function setOrder($field, $direction = Varien_Data_Collection::SORT_ORDER_DESC)
    {
        $this->_orders = array('field' => $field, 'direction' => $direction);

        return $this;
    }

} 