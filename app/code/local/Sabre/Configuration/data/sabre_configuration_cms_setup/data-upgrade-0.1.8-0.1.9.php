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
    <div class="item"><img alt="" src="{{skin url='images/pictos/r_cmd.svg'}}" />
        <h3>Commande<span>exp&eacute;di&eacute;e &agrave; J+1</span></h3>
    </div>
    <div class="item"><img alt="" src="{{skin url='images/pictos/r_livraison.svg'}}"  />
        <h3>Livraison offerte<span>d&egrave;s 100 &euro; d&rsquo;achat</span></h3>
    </div>
    <div class="item"><img alt="" src="{{skin url='images/pictos/r_conseils.svg'}}" />
        <h3>Conseillers et SAV<span> &agrave; votre service </span></h3>
    </div>
    <div class="item"><img alt="" src="{{skin url='images/pictos/r_cadeau.svg'}}" />
        <h3>Emballages<span>cadeaux</span></h3>
    </div>
    <div class="item"><img alt="" src="{{skin url='images/pictos/r_paiement.svg'}}" />
        <ul>
            <li><img alt="" src="{{skin url='images/pictos/r_cb.svg'}}" /></li>
            <li><img alt="" src="{{skin url='images/pictos/r_visa.svg'}}" /></li>
            <li><img alt="" src="{{skin url='images/pictos/r_mc.svg'}}" /></li>
            <li><img alt="" src="{{skin url='images/pictos/r_paypal.svg'}}" /></li>
        </ul>
        <h3>Paiement s&eacute;curis&eacute;</h3>
    </div>
</div>
</div>
HTML;

$contents = [

    'blocks' => [
        [
            'identifier' => 'reassurance',
            'content' => $reassurance,
            'title' => 'Réassurance Store'
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