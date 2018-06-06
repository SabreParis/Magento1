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

// create new store (english)
$sabreStoreEnglish = Mage::getModel('core/store');
$sabreStoreEnglish->load('sabre_fr_english','code')
    ->setName('English')
    ->setSortOrder('2')
    ->setIsActive(1)
    ->save();
?>