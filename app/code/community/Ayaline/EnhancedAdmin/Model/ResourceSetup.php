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

/**
 * Class Ayaline_EnhancedAdmin_Model_ResourceSetup
 *
 * @method Ayaline_EnhancedAdmin_Model_ResourceSetup setCode(string $code)
 * @method Ayaline_EnhancedAdmin_Model_ResourceSetup setType(string $code)
 * @method Ayaline_EnhancedAdmin_Model_ResourceSetup setVersion(string $code)
 * @method Ayaline_EnhancedAdmin_Model_ResourceSetup setFile(string $code)
 * @method Ayaline_EnhancedAdmin_Model_ResourceSetup setApplied(bool $applied)
 * @method Ayaline_EnhancedAdmin_Model_ResourceSetup setAppliedAt(string $appliedAt)
 *
 * @method string getCode()
 * @method string getType()
 * @method string getVersion()
 * @method bool getApplied()
 * @method string getAppliedAt()
 *
 * @method Ayaline_EnhancedAdmin_Model_Resource_ResourceSetup getResource()
 * @method Ayaline_EnhancedAdmin_Model_Resource_ResourceSetup _getResource()
 */
class Ayaline_EnhancedAdmin_Model_ResourceSetup extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        parent::_construct();
        $this->_init('ayaline_enhancedadmin/resourceSetup');
    }

    public function getHash()
    {
        if (!$this->_getData('hash')) {
            $this->setData('hash', md5("{$this->getCode()}_{$this->getType()}_{$this->getVersion()}"));
        }

        return $this->_getData('hash');
    }

}
