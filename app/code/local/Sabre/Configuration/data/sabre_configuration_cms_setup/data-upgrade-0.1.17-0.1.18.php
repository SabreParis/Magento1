<?php
/**
 * created : 2017.
 *
 * @category Ayaline
 *
 * @author aYaline
 * @copyright Ayaline - 2017 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();

$contentFR = <<<HTML
<div class="right">
<p>
{{customVar code=cart_header_fr}}
</p>
</div>
HTML;

Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load('livraison-offerte', 'identifier')
    ->setData('identifier', 'livraison-offerte')
    ->setData('content', $contentFR)
    ->setData('title', 'livraison offerte')
    ->setData('stores', $frenchStoreId)
    ->setIsActive(1)
    ->save();

$contentEN = <<<HTML
<div class="right">
<p>
{{customVar code=cart_header_en}}
</p>
</div>
HTML;

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load('livraison-offerte', 'identifier')
    ->setData('identifier', 'livraison-offerte')
    ->setData('content', $contentEN)
    ->setData('title', 'livraison offerte')
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save();

/**@var $variableFR Mage_Core_Model_Variable */
Mage::getModel('core/variable')
    ->loadByCode('cart_header_fr')
    ->setCode('cart_header_fr')
    ->setName('Cart Header fr')
    ->setHtmlValue('<span>Livraison offerte</span> <span>dès 80 € d\'achat en France métropolitaine</span>')
    ->setPlainValue('')
    ->save();

/**@var $variableFR Mage_Core_Model_Variable */
Mage::getModel('core/variable')
    ->loadByCode('cart_header_en')
    ->setCode('cart_header_en')
    ->setName('Cart Header en')
    ->setHtmlValue('<span>Free delivery</span> <span>From 80 € of purchase in Metropolitan France</span>')
    ->setPlainValue('')
    ->save();
