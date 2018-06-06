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
class Ayaline_Shop_Model_Shop_Group extends Mage_Core_Model_Abstract
{
    const PICTO_PATH_FOLDER = 'shops/group';
    const PICTO_PATH_IMAGE = 'image';

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('ayalineshop/shop_group');
    }

    public function _afterLoad()
    {
        if ($this->getIcon()) {
            $this->setOldIcon($this->getIcon());
            $this->setIcon(Mage::getBaseUrl('media') . self::PICTO_PATH_FOLDER . DS . $this->getIcon());
        }
        parent::_afterLoad();
    }

    public function upload($file)
    {
        if (isset($_FILES['icon']['name'])) {
            $path = Mage::getBaseDir('media') . DS . self::PICTO_PATH_FOLDER;
            try {
                $uploader = new Varien_File_Uploader('icon');
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $uploader->setAllowCreateFolders(true);


                $uplResult = $uploader->save($path, $_FILES['icon']['name']);

                $this->setIcon($uplResult['file']);
            } catch (Exception $e) {
                Mage::logException($e);
                throw new exception($e->getMessage());
            }
        }
    }


}
