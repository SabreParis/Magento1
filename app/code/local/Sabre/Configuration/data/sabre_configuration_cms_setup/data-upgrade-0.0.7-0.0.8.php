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


$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();

$frenchContent = <<<HTML
<p>Sabre utilise des cookies pour vous assurer un meilleur service. En continuant, vous acceptez l\'utilisation des cookies sur ce site. <a href="{{store url='confidentialite'}}">En savoir plus</a> ...</p><div class="clear-both">&nbsp;</div>
HTML;
$englishContent =<<<HTML
<p>Sabre uses cookies to ensure better service. By continuing, you  are accepting the use of cookies on this site. <a href="{{store url='confidentialite'}}">Read more</a> ...</p><div class="clear-both">&nbsp;</div>
HTML;

$blockCockies = array('identifier'=>'cms-cockies','title'=>'cms cockies message');


$_block = Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($blockCockies['identifier'], 'identifier');
$_block->setData('identifier', $blockCockies['identifier']);
$_block->setData('content', $frenchContent);
$_block->setData('title', $blockCockies['title']);
$_block->setData('stores', $frenchStoreId);
$_block->setIsActive(1);
$_block->save();


$_block = Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($blockCockies['identifier'], 'identifier');
$_block->setData('identifier', $blockCockies['identifier']);
$_block->setData('content', $englishContent);
$_block->setData('title', $blockCockies['title']);
$_block->setData('stores', $englishStoreId);
$_block->setIsActive(1);
$_block->save();