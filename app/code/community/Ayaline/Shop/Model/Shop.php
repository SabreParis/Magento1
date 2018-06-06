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
 * Shop model
 *
 */
class Ayaline_Shop_Model_Shop extends Mage_Core_Model_Abstract
{
    const PICTO_PATH_FOLDER = 'shops';
    const PICTO_PATH_IMAGE = 'image';
    protected $_group = null;
    protected $_currentOrderTotal = null;

    protected $_days = array(
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday'
    );

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('ayalineshop/shop');
    }

    public function _beforeSave()
    {
        //Titre magasin
        if (!$this->getTitle()) {
            $group = Mage::getModel('ayalineshop/shop_group')->load($this->getGroupId());
            $this->setTitle($group->getName() . ' - ' . $this->getCity());
        }
        parent::_beforeSave();
    }

    public function _afterSave()
    {
        $this->_saveSchecules();
        $this->_saveSpecilasSchecules();
        $this->_savePostcodes();
        $this->_saveStoreRelations();
        parent::_afterSave();
    }

    public function _afterLoad()
    {
        if ($this->getPicture()) {
            $this->setOldPicture($this->getPicture());
            $this->setPicture(Mage::getBaseUrl('media') . self::PICTO_PATH_FOLDER . '/' . $this->getPicture());
        }
        if ($this->getMarker()) {
            $this->setOldMarker($this->getMarker());
            $this->setMarker(Mage::getBaseUrl('media') . self::PICTO_PATH_FOLDER . '/' . $this->getMarker());
        }
        // Get stores
        $shop = $this->getResource()->prepareStores($this);
        $this->setStores($shop->getStores());
        parent::_afterLoad();
    }


    public function exists($idShop = null)
    {
        return $this->getResource()->exists($idShop);
    }

    /**
     * Upload l'image du magasin
     *
     * @param $file
     */
    public function upload($file, $name)
    {
        if (isset($_FILES['picture']['name']) || isset($_FILES['marker']['name'])) {
            $path = Mage::getBaseDir('media') . DS . self::PICTO_PATH_FOLDER;
            try {
                $uploader = new Varien_File_Uploader($name);
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $uploader->setAllowCreateFolders(true);


                $uplResult = $uploader->save($path, $_FILES[$name]['name']);

                $this->setData($name, $uplResult['file']);
            } catch (Exception $e) {
                Mage::logException($e);
                throw new exception($e->getMessage());
            }
        }
    }

    /**
     * Horaires d'ouvertures
     *
     * @return Ayaline_Shop_Model_Mysql4_Shop_Schedules_Collection
     */
    public function getSchedules($isAdmin = false)
    {
        if (!$this->getData('schedules')) {
            if ($this->getId()) {
                $schedules = Mage::getModel('ayalineshop/shop_schedules')
                                 ->getCollection()
                                 ->addShopFilter($this->getId());
                if ($isAdmin) {
                    $schedules->addOrderBySortDesc();
                } else {
                    $schedules->addOrderBySort();
                }

                if (!$schedules->getSize()) {
                    $this->prepareDefaultSchedules();
                } else {
                    $this->setData('schedules', $schedules);
                }
            } else {
                $this->prepareDefaultSchedules();
            }
        }

        return $this->getData('schedules');
    }

    /**
     * Horaires d'ouvertures spéciaux
     *
     * @return Ayaline_Shop_Model_Mysql4_Shop_SpecialsSchedules_Collection
     */
    public function getSpecialsSchedules($isAdmin = false)
    {
        if (!$this->getData('specialsSchedules')) {
            if ($this->getId()) {
                $schedules = Mage::getModel('ayalineshop/shop_specialsSchedules')
                                 ->getCollection()
                                 ->addShopFilter($this->getId());
                if ($isAdmin) {
                    $schedules->addOrderBySortDesc();
                } else {
                    $schedules->addOrderBySort();
                }
                $this->setData('specialsSchedules', $schedules);
            }
        }

        return $this->getData('specialsSchedules');
    }

    /**
     * Zones de chalandise
     *
     * @return Ayaline_Shop_Model_Mysql4_Shop_Postcode_Collection
     */
    public function getPostcodes()
    {
        if (!$this->getData('postcodes')) {
            if ($this->getId()) {
                $postcodes = Mage::getModel('ayalineshop/shop_postcode')
                                 ->getCollection()
                                 ->addShopFilter($this->getId());

                $this->setData('postcodes', $postcodes);
            }
        }

        return $this->getData('postcodes');
    }

    /**
     * Prepare default fields
     */
    public function prepareDefaultSchedules()
    {
        $schedules = array();
        $sort = 0;
        foreach ($this->_days as $day) {
            $s = Mage::getModel('ayalineshop/shop_schedules');
            $s->setDay(Mage::helper('ayalineshop')->__($day));
            $s->setSort($sort);
            $schedules[] = $s;
            $sort++;
        }
        $schedules = array_reverse($schedules);
        $this->setData('schedules', $schedules);

        return $this;
    }

    /**
     * Sauvegarde les horaires d'ouverture
     */
    protected function _saveSchecules()
    {
        if ($this->getSchedulesData() && is_array($this->getSchedulesData())) {
            foreach ($this->getSchedulesData() as $data) {
                $schedules = Mage::getModel('ayalineshop/shop_schedules');
                if ($data['id']) {
                    $schedules->load($data['id']);
                }
                $schedules->setDay($data['day']);
                $schedules->setHourStartPm($data['hour_start_pm']);
                $schedules->setHourEndPm($data['hour_end_pm']);
                $schedules->setHourStartAm($data['hour_start_am']);
                $schedules->setHourEndAm($data['hour_end_am']);
                $schedules->setSort($data['sort']);

                $schedules->setShopId($this->getId());

                $schedules->save();
            }

            $this->setSchedulesData(null);
        }
    }


    /**
     * Sauvegarde les horaires d'ouverture spéciaux
     */
    protected function _saveSpecilasSchecules()
    {
        if ($this->getSpecialsSchedulesData() && is_array($this->getSpecialsSchedulesData())) {
            foreach ($this->getSpecialsSchedulesData() as $data) {
                $schedules = Mage::getModel('ayalineshop/shop_specialsSchedules');
                if ($data['delete'] == 1) {
                    if ($data['id']) {
                        $schedules->load($data['id']);
                        $schedules->delete();
                    }
                    continue;
                }
                if ($data['id']) {
                    $schedules->load($data['id']);
                }
                $schedules->setDay($data['day']);
                $schedules->setHourStartPm($data['hour_start_pm']);
                $schedules->setHourEndPm($data['hour_end_pm']);
                $schedules->setHourStartAm($data['hour_start_am']);
                $schedules->setHourEndAm($data['hour_end_am']);
                $schedules->setSort($data['sort']);

                $schedules->setShopId($this->getId());

                $schedules->save();
            }

            $this->setSpecialsSchedulesData(null);
        }
    }


    /**
     * Sauvegarde les zones de chalandises
     */
    protected function _savePostcodes()
    {
        if ($this->getPostcodeData() && is_array($this->getPostcodeData())) {
            foreach ($this->getPostcodeData() as $data) {
                $postcode = Mage::getModel('ayalineshop/shop_postcode');
                if ($data['delete'] == 1) {
                    if ($data['id']) {
                        $postcode->load($data['id']);
                        $postcode->delete();
                    }
                    continue;
                }
                if ($data['id']) {
                    $postcode->load($data['id']);
                }
                $postcode->setPostcode($data['postcode']);
                $postcode->setShopId($this->getId());
                $postcode->save();
            }

            $this->setPostcodeData(null);
        }
    }

    /**
     * Save store relations
     */
    protected function _saveStoreRelations()
    {
        if ($this->getStores() && is_array($this->getStores())) {
            $this->getResource()->saveStoreRelations($this);
        }
    }

    public function getContactInShipping()
    {
        $formater = new Varien_Filter_Template();
        $var = array();
        $var['title'] = $this->getTitle();
        $var['street1'] = $this->getStreet1();
        $var['street2'] = $this->getStreet2();
        $var['postcode'] = $this->getPostcode();
        $var['city'] = strtoupper($this->getCity());
        $var['telephone'] = $this->getTelephone();
        $var['email'] = $this->getEmail();
        $var['map_link'] = Mage::getUrl('ayalineshop/index/view/id/' . $this->getId());
        !!
        $schedules = $this->getSchedules();
        /* @var $schedule Ayaline_Shop_Model_Shop_Schedules */
        foreach ($schedules as $schedule) {
            $amStart = $schedule->getHourStartPm(); // /!\ PM est le matin
            $amEnd = $schedule->getHourEndPm(); // /!\ PM est le matin
            $pmStart = $schedule->getHourStartAm();    // /!\ AM est l'après midi
            $pmEnd = $schedule->getHourEndAm();    // /!\ AM est l'après midi

            $am = '';
            $daySplit = true;
            $pm = '';
            $horaire = '';
            if ($amStart && $amEnd) {
                $am = $amStart . '-' . $amEnd;
            } elseif ($amStart && !$amEnd) {
                $am = $amStart;
                $daySplit = false;
            }

            if ($pmStart && $pmEnd) {
                $pm = $pmStart . '-' . $pmEnd;
            } elseif (!$pmStart && $pmEnd) {
                $pm = $pmEnd;
                $daySplit = false;
            }

            if ($am && $pm && $daySplit) {
                $horaire = $am . ' / ' . $pm;
            } elseif ($am && $pm && !$daySplit) {
                $horaire = $am . '-' . $pm;
            } elseif ($am && !$pm) {
                $horaire = $am;
            } elseif (!$am && $pm) {
                $horaire = $pm;
            }

            if ($horaire) {
                $var[$schedule->getDay()] = $horaire;
            }
        }

        $formater->setVariables($var);
        $format = Mage::getShopConfig('ayalineshop/address_templates/html', Mage::app()->getShop());

        return $formater->filter($format);
    }

    /**
     * Retourne l'enseigne du magasin
     *
     * @return Ayaline_Shop_Model_Shop_Group
     */
    public function getShopGroup()
    {
        if ($this->_group == null) {
            $this->_group = Mage::getModel('ayalineshop/shop_group')->load($this->getGroupId());
        }

        return $this->_group;
    }

    public function getCountry()
    {
        if ($this->getData('country_id') && !$this->getData('country')) {
            $this->setData('country', Mage::getModel('directory/country')->load($this->getData('country_id'))->getName());
        }

        return $this->getData('country');
    }
}
