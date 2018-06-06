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
$FrHeaderMenu = <<<HTML
<div class="ceMoment">
    <a href="#">
        <p>En ce moment, <span>SABRE avec Akin & Suri…</span></p>
        <img src="{{skin url='images/ce-moment.png'}}" alt="bg"/>
    </a>
</div>
HTML;
$EnHeaderMenu = <<<HTML
<div class="ceMoment">
    <a href="#">
        <p>At this moment, <span>SABRE with Akin & Suri ...</span></p>
        <img src="{{skin url='images/ce-moment.png'}}" alt="bg"/>
    </a>
</div>
HTML;


$blockHeaderMenu = array('identifier' => 'entete-menu', 'title' => 'entete du menu');

$_blockFr = Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($blockHeaderMenu['identifier'], 'identifier');
$_blockFr->setData('identifier', $blockHeaderMenu['identifier']);
$_blockFr->setData('content', $FrHeaderMenu);
$_blockFr->setData('title', $blockHeaderMenu['title']);
$_blockFr->setData('stores', $frenchStoreId);
$_blockFr->setIsActive(1);
$_blockFr->save();


$_blockEn = Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($blockHeaderMenu['identifier'], 'identifier');
$_blockEn->setData('identifier', $blockHeaderMenu['identifier']);
$_blockEn->setData('content', $EnHeaderMenu);
$_blockEn->setData('title', $blockHeaderMenu['title']);
$_blockEn->setData('stores', $englishStoreId);
$_blockEn->setIsActive(1);
$_blockEn->save();