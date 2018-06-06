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
class Ayaline_EnhancedAdmin_Model_System_Source_Module_Version
{

    protected $_options = array();
    protected $_optionArray = array();

    protected function _addVersion($setup, $versions, $file, $type, $mode = 'version')
    {
        if ($mode) {
            $_to = $versions;
        } else {
            $_versions = explode('-', $versions);
            $_to = end($_versions);
        }

        $_installOrUpgrade = 'install';
        if (preg_match('#upgrade#', $file)) {
            $_installOrUpgrade = 'upgrade';
        }

        if (array_key_exists($_to, $this->_options[$setup->getId()][$mode])) {
            $this->_options[$setup->getId()][$mode][$_to] = preg_replace('#\[([a-z]+)\]#i', "[$1 & {$type}]", $this->_options[$setup->getId()][$mode][$_to]);
        } else {
            $this->_options[$setup->getId()][$mode][$_to] = sprintf('%s [%s] (%s)', $_to, $type, $_installOrUpgrade);
        }


    }

    /**
     * @param Varien_Object $setup
     * @param string        $mode
     * @return array
     */
    public function getOptions($setup, $mode = 'version')
    {
        if (!array_key_exists($setup->getId(), $this->_options)) {
            $this->_options[$setup->getId()] = array();
        }

        if (!array_key_exists($mode, $this->_options[$setup->getId()])) {
            $this->_options[$setup->getId()][$mode] = array();

            foreach ($setup->getDbFiles() as $_versions => $_file) {
                $this->_addVersion($setup, $_versions, $_file, 'sql', $mode);
            }

            foreach ($setup->getDataFiles() as $_versions => $_file) {
                $this->_addVersion($setup, $_versions, $_file, 'data', $mode);
            }

            uksort($this->_options[$setup->getId()][$mode], 'version_compare');
        }

        return $this->_options[$setup->getId()][$mode];
    }

    public function toOptionArray($setup, $mode = 'version')
    {
        if (!array_key_exists($setup->getId(), $this->_optionArray)) {
            $this->_optionArray[$setup->getId()] = array();
        }

        if (!array_key_exists($mode, $this->_optionArray[$setup->getId()])) {
            $this->_optionArray[$setup->getId()][$mode] = array();
            foreach ($this->getOptions($setup, $mode) as $_value => $_label) {
                $this->_optionArray[$setup->getId()][$mode][] = array('value' => $_value, 'label' => $_label);
            }
        }

        return $this->_optionArray[$setup->getId()][$mode];
    }

}
