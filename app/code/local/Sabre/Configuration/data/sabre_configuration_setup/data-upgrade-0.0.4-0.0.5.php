<?php
/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/** @var $this Mage_Core_Model_Resource_Setup */
//Payment Methods configuration

$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();


//Bank Transfer Payment

$this->setConfigData('payment/banktransfer/active',1);
//EN
$this->setConfigData('payment/banktransfer/title','Bank Transfer Payment', 'stores', $englishStoreId);
//FR
$this->setConfigData('payment/banktransfer/title','Paiement par virement bancaire','stores',$frenchStoreId);

//Check / Money order

$this->setConfigData('payment/checkmo/active',1);
//EN
$this->setConfigData('payment/checkmo/title','Check', 'stores', $englishStoreId);
//FR
$this->setConfigData('payment/checkmo/title','Chèque','stores',$frenchStoreId);

//Cybermut payment

$this->setConfigData('payment/cybermut_payment/active',1);
//EN
$this->setConfigData('payment/cybermut_payment/title','Payment by credit card', 'stores', $englishStoreId);
//FR
$this->setConfigData('payment/cybermut_payment/title','Paiement par carte bancaire','stores',$frenchStoreId);

$this->setConfigData('payment/cybermut_payment/version',3.0);
//EN
$this->setConfigData('payment/cybermut_payment/language','EN', 'stores', $englishStoreId);
//FR
$this->setConfigData('payment/cybermut_payment/language','FR', 'stores', $frenchStoreId);

$this->setConfigData('payment/cybermut_payment/test/mode',1);

$this->setConfigData('payment/cybermut_payment/debug',0);

$this->setConfigData('payment/cybermut_payment/invoice/create',0);

$this->setConfigData('payment/cybermut_payment/empty/cart',0);


