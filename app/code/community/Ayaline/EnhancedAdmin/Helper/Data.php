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
class Ayaline_EnhancedAdmin_Helper_Data extends Mage_Core_Helper_Abstract
{

    protected $_installDate = null;

    /**
     * Check if Magento version is greater than equal as 1.6.0.0
     *
     * @return bool
     */
    public function useMysql4()
    {
        if (version_compare(Mage::getVersion(), '1.6.0.0', 'lt')) { //  Community
            return true;
        }

        if (version_compare(Mage::getVersion(), '1.11.0.0', 'lt')) { //  Enterprise
            return true;
        }

        return false;
    }

    /**
     * Add custom column renderer and filter
     *
     * @param Mage_Adminhtml_Block_Widget_Grid $grid
     * @return void
     */
    public function addGridRendererAndFilter($grid)
    {
        $renderers = $grid->getColumnRenderers();
        $renderers['setup_version'] = 'ayaline_enhancedadmin/adminhtml_widget_grid_renderer_select_version';
        $renderers['setup_versions'] = 'ayaline_enhancedadmin/adminhtml_widget_grid_renderer_text_versions';
        $renderers['file_action'] = 'ayaline_enhancedadmin/adminhtml_widget_grid_renderer_action_file';
        $renderers['view_file_action'] = 'ayaline_enhancedadmin/adminhtml_widget_grid_renderer_action_viewFile';
        $grid->setColumnRenderers($renderers);
    }

    /**
     * Re-init config cache
     */
    public function reinitConfigCache()
    {
        $configOptions = Mage::app()->getConfig()->getOptions();
        Mage::app()->getConfig()->reinit($configOptions->getData());
    }

    public function getInstallDate()
    {
        if ($this->_installDate === null) {
            $install = (string)Mage::getConfig()->getNode('global/install/date');
            if ($install && $install !== Mage_Install_Model_Installer_Config::TMP_INSTALL_DATE_VALUE) {
                $install = DateTime::createFromFormat(DateTime::RFC2822, $install);
            } else {
                $install = new DateTime();
            }
            $this->_installDate = $install->format(Varien_Date::DATETIME_PHP_FORMAT);
        }

        return $this->_installDate;
    }

}