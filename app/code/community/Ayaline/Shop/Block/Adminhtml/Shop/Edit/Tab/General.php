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
 *  Shop general form block
 *
 */
class Ayaline_Shop_Block_Adminhtml_Shop_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initForm()
    {
        $form = new Varien_Data_Form();

        $shop = Mage::registry('current_ayaline_shop');

        $fieldset = $form->addFieldset('shop_form',
                                       array(
                                           'legend' => Mage::helper('ayalineshop')->__('Informations')
                                       )
        );

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );

        $isElementDisabled = false;
        if (!Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_shop/actions/update')
            && !Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_shop/actions/create')
        ) {
            $isElementDisabled = true;
        }

        //Administrateur seulement
        if (Mage::getSingleton('admin/session')->isAllowed('ayaline/ayalineshop_manage_shop/actions/view_admin')) {
            //Enseigne
            $fieldset->addField('group_id', 'select',
                                array(
                                    'label'    => Mage::helper('ayalineshop')->__('Shop Group'),
                                    'class'    => 'required-entry',
                                    'required' => true,
                                    'name'     => 'group_id',
                                    'options'  => Mage::getModel('ayalineshop/shop_group')->getCollection()->addOrderByName()->getArray(),
                                    'disabled' => $isElementDisabled
                                ));

            //Etat
            $fieldset->addField('is_active', 'select',
                                array(
                                    'label'    => Mage::helper('ayalineshop')->__('State'),
                                    'class'    => 'required-entry',
                                    'required' => true,
                                    'name'     => 'is_active',
                                    'options'  => array(
                                        true  => Mage::helper('adminhtml')->__('Yes'),
                                        false => Mage::helper('adminhtml')->__('No')
                                    ),
                                    'disabled' => $isElementDisabled
                                )
            );

            /**
             * Check is single store mode
             */
            if (!Mage::app()->isSingleStoreMode()) {
                $fieldset->addField('stores', 'multiselect', array(
                    'name'     => 'stores[]',
                    'label'    => Mage::helper('cms')->__('Store View'),
                    'title'    => Mage::helper('cms')->__('Store View'),
                    'required' => true,
                    'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                ));
            } else {
                $fieldset->addField('stores', 'hidden', array(
                    'name'  => 'stores[]',
                    'value' => Mage::app()->getStore(true)->getId()
                ));
                $shop->setStores(Mage::app()->getStore(true)->getId());
            }
        }
        //Titre du magasin
        $fieldset->addField('title', 'text',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Title'),
                                'name'     => 'title',
                                'disabled' => $isElementDisabled

                            )
        );

        //Description
        $fieldset->addField('description', 'textarea',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Description'),
                                'required' => false,
                                'name'     => 'description',
                                'disabled' => $isElementDisabled
                            )
        );

        //Adresse 1
        $fieldset->addField('street1', 'text',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Address'),
                                'class'    => 'required-entry',
                                'required' => true,
                                'name'     => 'street1',
                                'disabled' => $isElementDisabled
                            )
        );

        //Adresse 2
        $fieldset->addField('street2', 'text',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Additional address'),
                                'name'     => 'street2',
                                'disabled' => $isElementDisabled
                            )
        );

        //Code postal
        $fieldset->addField('postcode', 'text',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Postcode'),
                                'class'    => 'required-entry',
                                'required' => true,
                                'name'     => 'postcode',
                                'disabled' => $isElementDisabled
                            )
        );

        //Ville
        $fieldset->addField('city', 'text',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('City'),
                                'class'    => 'required-entry',
                                'required' => true,
                                'name'     => 'city',
                                'disabled' => $isElementDisabled
                            )
        );

        //Pays
        $fieldset->addField('country_id', 'select',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Country'),
                                'class'    => 'required-entry',
                                'required' => true,
                                'name'     => 'country_id',
                                'options'  => Mage::helper('ayalineshop')->getCountriesOption(),
                                'disabled' => $isElementDisabled
                            ));

        //Téléphone
        $fieldset->addField('telephone', 'text',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Telephone'),
                                'name'     => 'telephone',
                                'disabled' => $isElementDisabled
                            ));

        //Fax
        $fieldset->addField('fax', 'text',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Fax'),
                                'name'     => 'fax',
                                'disabled' => $isElementDisabled
                            ));

        //Email
        $fieldset->addField('email', 'textarea',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Email'),
                                'name'     => 'email',
                                //'class' => 'validate-email',
                                'disabled' => $isElementDisabled
                            ));

        //Image
        $fieldset->addField('picture', 'image',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Image'),
                                'class'    => 'input-text',
                                'name'     => 'picture',
                                'required' => false,
                                'note'     => Mage::helper('ayalineshop')->__('Extensions allowed: jpg, jpeg, gif and png')
                            )
        );
        //Latitude
        $fieldset->addField('latitude', 'text',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Latitude'),
                                'class'    => 'required-entry',
                                'required' => true,
                                'name'     => 'latitude',
                                'disabled' => $isElementDisabled
                            ));

        //Longitude
        $fieldset->addField('longitude', 'text',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Longitude'),
                                'class'    => 'required-entry',
                                'required' => true,
                                'name'     => 'longitude',
                                'disabled' => $isElementDisabled
                            ));

        //Marqueur
        $fieldset->addField('marker', 'image',
                            array(
                                'label'    => Mage::helper('ayalineshop')->__('Marker'),
                                'required' => false,
                                'name'     => 'marker',
                                'disabled' => $isElementDisabled,
                                'note'     => Mage::helper('ayalineshop')->__('Extensions allowed: jpg, jpeg, gif and png') . ' ' . Mage::helper('ayalineshop')->__('Size : 30x30')
                            ));

        $form->setValues($shop->getData());
        $this->setForm($form);

        return $this;
    }

}
