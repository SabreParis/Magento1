<?php
/**
 * created : 2016
 *
 * @category Ayaline
 * @package Ayaline_XXXX
 * @author aYaline
 * @copyright Ayaline - 2016 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();


//block Réassurance
$reassurance=
    <<<HTML
<div class="reasurance">
    <div>
        <div class="item">
            <div class="cmd"></div>
            <h3>Commande<span>exp&eacute;di&eacute;e &agrave; J+1</span></h3>
        </div>
        <div class="item">
            <div class="livraison"></div>
            <h3>Livraison offerte <span>d&egrave;s 100 &euro; d&rsquo;achat<span>(en France m&eacute;tropolitaine)<br /></span></span></h3>
        </div>
        <div class="item">
            <div class="conseils"></div>
            <h3>Conseillers et SAV<span> &agrave; votre service </span></h3>
        </div>
        <div class="item">
            <div class="cadeau"></div>
            <h3>Emballages<span>cadeaux</span></h3>
        </div>
        <div class="item">
            <div class="paiement"></div>
            <ul>
                <li><div class="cb"></div></li>
                <li><div class="visa"></div></li>
                <li><div class="mc"></div></li>
                <li><div class="paypal"></div></li>
            </ul>
            <h3>Paiement s&eacute;curis&eacute;</h3>
        </div>
    </div>
</div>
HTML;

$rs=
    <<<HTML
<div class="rs">
<p>Suivez-nous</p>
<ul>
    <li><a href='https://www.facebook.com/pages/SABRE-PARIS/131241179309'><div class="fb"></div></a></li>
    <li><a href='https://twitter.com/SabreParis'> <div class="tw"></div></a></li>
    <li><a href="http://www.pinterest.com/sabreparis/"><div class="pn"></div></a></li>
</ul>
</div>
HTML;



$contents = [

    'blocks' => [
        [
            'identifier' => 'reassurance',
            'content' => $reassurance,
            'title' => 'Réassurance Store'
        ],
        [
            'identifier' => 'reseaux-sociaux',
            'content' => $rs,
            'title' => 'Réseaux sociaux Store'
        ]

    ]

];

foreach ($contents as $_type => $_contentsType) {
    foreach ($_contentsType as $_content) {

        $_block = Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($_content['identifier'], 'identifier');
        $_block->setData('identifier', $_content['identifier']);
        $_block->setData('content', $_content['content']);
        $_block->setData('title', $_content['title']);
        $_block->setData('stores', [$frenchStoreId]);
        $_block->setIsActive(1);
        $_block->save();

        $_block = Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($_content['identifier'], 'identifier');
        $_block->setData('identifier', $_content['identifier']);
        $_block->setData('content', $_content['content']);
        $_block->setData('title', $_content['title']);
        $_block->setData('stores', [$englishStoreId]);
        $_block->setIsActive(1);
        $_block->save();

    }
}





