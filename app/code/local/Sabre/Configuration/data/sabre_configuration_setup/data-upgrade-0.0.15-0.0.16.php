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


//sabre shop


/** @var $this Mage_Core_Model_Resource_Setup */

$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();

$this->setConfigData('carriers/sabreshop/active',1);

$this->setConfigData('carriers/sabreshop/price',0);

$this->setConfigData('carriers/sabreshop/specificerrmsg','This shipping method is currently unavailable. If you would like to ship using this shipping method, please contact us.','stores', $englishStoreId);
$this->setConfigData('carriers/sabreshop/specificerrmsg',"Cette méthode de transport est actuellement indisponible. Si vous souhaitez expédier en utilisant cette méthode d'expédition, s'il vous plaît contactez-nous.",'stores', $frenchStoreId);



$this->setConfigData('carriers/sabreshop/moreinfo',"Command to withdraw shop on presentation of your order and your identity card. You can withdraw it after receiving an email confirming the availability in stores Time: usually under 48 hours.",'stores', $englishStoreId);

$this->setConfigData('carriers/sabreshop/moreinfo',"Commande à retirer en magasin sur présentation de votre bon de commande et de votre pièce d'identité. Vous pouvez ainsi la retirer après réception d'un email vous confirmant la mise à disposition en magasin Délais : généralement sous 48h",'stores', $frenchStoreId);


