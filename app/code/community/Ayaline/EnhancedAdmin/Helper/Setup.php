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
class Ayaline_EnhancedAdmin_Helper_Setup extends Ayaline_EnhancedAdmin_Helper_Data
{

    protected $_modules = null;

    protected $_pathUpdated = false;

    /**
     * Local cache of module.
     *  on first call we "re-calculate" config cache (files only)
     *
     * @return Mage_Core_Model_Config
     */
    public function getModules()
    {
        if ($this->_modules === null) {
            $this->_modules = Mage::getConfig()->loadModules();
        }

        return $this->_modules;
    }

    /**
     * Get path to setup class cache
     *
     * @return string
     */
    protected function _getCachePath()
    {
        $path = Mage::getBaseDir('cache') . DS . 'ayaline' . DS . 'enhanced_admin' . DS . 'setup_class';
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        return $path;
    }

    /**
     * Retrieve setup template file (use config)
     *
     * @return string
     */
    protected function _getTemplateFile()
    {
        $templateConfig = Mage::getConfig()->getNode('enhanced_admin/setup/template');

        $template = Mage::getModuleDir('', $templateConfig->module->__toString()) . DS . $templateConfig->file->__toString();

        return $template;
    }

    /**
     * Add setup_class path into default path
     */
    public function updateIncludePath()
    {
        if (!$this->_pathUpdated) {
            $this->_pathUpdated = true;
            $paths = get_include_path();
            set_include_path($this->_getCachePath() . PS . $paths);
        }
    }

    /**
     * Create setup class file and return class name
     *
     * @param $extendsClassName
     * @return string
     */
    public function createClass($extendsClassName)
    {
        $templateFile = $this->_getTemplateFile();

        $io = new Varien_Io_File();
        $io->open(array('path' => pathinfo($templateFile, PATHINFO_DIRNAME)));

        if (!$io->fileExists(pathinfo($templateFile, PATHINFO_BASENAME))) {
            Mage::throwException("Can't find setup template file");
        }

        // check if class already exists
        $className = explode('_', $extendsClassName);
        $className = $className[1] . end($className); // transform Mage_Catalog_Model_Resource_Setup into CatalogSetup
        $classFile = $this->_getCachePath() . DS . "{$className}.php";
        if ($io->fileExists($classFile)) {
            return $className;
        }

        $classTemplate = $io->read(pathinfo($templateFile, PATHINFO_BASENAME));
        $filterTemplate = new Varien_Filter_Template();
        $filterTemplate->setVariables(array(
                                          'className'        => $className,
                                          'extendsClassName' => $extendsClassName,
                                      ));
        $classContent = $filterTemplate->filter($classTemplate);

        if (!$io->write($classFile, $classContent)) {
            Mage::throwException("Can't write setup class");
        }

        return $className;
    }

    /**
     * Remove setup class cache
     *
     * @return bool
     */
    public function flushClasses()
    {
        $io = new Varien_Io_File();
        $io->open(array('path' => $this->_getCachePath()));

        return $io->rmdir($this->_getCachePath(), true);
    }

}
