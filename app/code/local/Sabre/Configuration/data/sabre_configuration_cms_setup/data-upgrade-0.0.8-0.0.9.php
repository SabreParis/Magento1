<?php
$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();

$_content=<<<HTML
<div class="right">
<p>
<span>Livraison offerte</span>
<span>dès 100 € d'achat</span>
</p>
</div>
HTML;

$_content1=<<<HTML
<div class="text-footer-checkout">
<p>
Texte à modifier, LA FLEXIBILITÉ EST UNE NOTION IMPORTANTE CHEZ SABRE,
ON LA CULTIVE POUR LA PLUS GRANDE SATISFACTION DE LA CLIENTÈLE.
Leur valeur ajoutée c'est de pouvoir produire à la commande, et ainsi de permettre aux clients(es) de composer leur service sur mesure.
</p>
</div>
HTML;


$_block = Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load('livraison-offerte', 'identifier');
$_block->setData('identifier', 'livraison-offerte');
$_block->setData('content', $_content);
$_block->setData('title', 'livraison offerte');
$_block->setData('stores', $frenchStoreId);
$_block->setIsActive(1);
$_block->save();

$_block = Mage::getModel('cms/block')->setStoreId($englishStoreId)->load('livraison-offerte', 'identifier');
$_block->setData('identifier', 'livraison-offerte');
$_block->setData('content', $_content);
$_block->setData('title', 'livraison offerte');
$_block->setData('stores', $englishStoreId);
$_block->setIsActive(1);
$_block->save();


$_block1 = Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load('text-footer', 'identifier');
$_block1->setData('identifier', 'text-footer');
$_block1->setData('content', $_content1);
$_block1->setData('title', 'texte footer');
$_block1->setData('stores', $frenchStoreId);
$_block1->setIsActive(1);
$_block1->save();

$_block1 = Mage::getModel('cms/block')->setStoreId($englishStoreId)->load('text-footer', 'identifier');
$_block1->setData('identifier', 'text-footer');
$_block1->setData('content', $_content1);
$_block1->setData('title', 'text footer');
$_block1->setData('stores', $englishStoreId);
$_block1->setIsActive(1);
$_block1->save();