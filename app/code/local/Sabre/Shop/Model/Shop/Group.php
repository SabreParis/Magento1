<?php
/**
 * created : 10/03/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Shop
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 * Shop Group model
 *
 */
class Sabre_Shop_Model_Shop_Group extends Ayaline_Shop_Model_Shop_Group
{

    public function _afterLoad()
    {
        parent::_afterLoad();
        if ($this->getMarker()) {
            $this->setOldMarker($this->getMarker());
            $this->setMarker(Mage::getBaseUrl('media') . self::PICTO_PATH_FOLDER . DS . $this->getMarker());
        }
        return $this;
    }

    public function upload($attributeCode = 'icon')
    {
        if (isset($_FILES[$attributeCode]['name'])) {
            $path = Mage::getBaseDir('media') . DS . self::PICTO_PATH_FOLDER;
            try {
                $uploader = new Varien_File_Uploader($attributeCode);
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $uploader->setAllowCreateFolders(true);

                $uplResult = $uploader->save($path, $_FILES[$attributeCode]['name']);

                $this->setData($attributeCode,$uplResult['file']);
            } catch (Exception $e) {
                Mage::logException($e);
                throw new exception($e->getMessage());
            }
        }
    }


}
