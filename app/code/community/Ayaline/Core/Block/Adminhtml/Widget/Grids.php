<?php

/**
 * created : 2014
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_Core_Block_Adminhtml_Widget_Grids extends Mage_Adminhtml_Block_Widget_Container
{

    protected $_grids = array();

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('ayaline/core/widget/grids.phtml');
    }

    /**
     * Add grid in container
     *
     * @param string       $gridId
     * @param array|string $grid
     * @return $this
     * @throws Exception
     */
    public function addGrid($gridId, $grid)
    {
        if (is_array($grid)) {
            $this->_grids[$gridId] = new Varien_Object($grid);
        } elseif (is_string($grid)) {
            if (strpos($grid, '/')) {
                $this->_grids[$gridId] = $this->getLayout()->createBlock($grid);
            } elseif ($this->getChild($grid)) {
                $this->_grids[$gridId] = $this->getChild($grid);
            } else {
                $this->_grids[$gridId] = null;
            }

            if (!($this->_grids[$gridId] instanceof Ayaline_Core_Block_Adminhtml_Widget_Grid_Interface)) {
                throw new Exception($this->__('Wrong grid configuration.'));
            }
        } else {
            throw new Exception($this->__('Wrong grid configuration.'));
        }

        return $this;
    }

    /**
     * Render grids
     *
     * @return string
     */
    public function getGridsHtml()
    {
        $html = '';
        /** @var $_grid Mage_Adminhtml_Block_Widget_Grid */
        foreach ($this->_grids as $_grid) {
            if ($_grid->canShowGrid()) {
                if ($_grid->isHidden()) {
                    $html .= '<div style="display: none;">';
                }
                $html .= $_grid->toHtml();
                if ($_grid->isHidden()) {
                    $html .= '</div>';
                }
            }
        }

        return $html;
    }

    /**
     * Get header CSS class
     *
     * @return string
     */
    public function getHeaderCssClass()
    {
        return 'icon-head ' . parent::getHeaderCssClass();
    }

    /**
     * @return string
     */
    public function getHeaderWidth()
    {
        return 'width:50%;';
    }

} 