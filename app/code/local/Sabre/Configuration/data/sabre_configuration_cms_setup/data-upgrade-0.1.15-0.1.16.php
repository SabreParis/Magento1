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


//block infos product - product view
$infos=
    <<<HTML
        <img src="{{skin url='images/product-infos-1.png'}}" alt="Passe au lave-vaisselle à basse température." />
        <img src="{{skin url='images/product-infos-2.png'}}" alt="Passe au lave-vaisselle à basse température." />
        <span class="text">
            Passe au lave-vaisselle<br/>à basse température.
        </span>
HTML;

$content_infos = [

    'blocks' => [
        [
            'identifier' => 'product_info',
            'content' => $infos,
            'title' => 'Informations du produit'
        ]
    ]

];

foreach ($content_infos as $_type => $_contentsType) {
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