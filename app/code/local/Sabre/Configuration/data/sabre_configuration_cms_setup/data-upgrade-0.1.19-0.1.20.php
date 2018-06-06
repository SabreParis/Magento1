<?php
/**
 * created: 2017
 *
 * @category  XXXXXXX
 * @package   Ayaline
 * @author    aYaline Magento <support.magento-shop@ayaline.com>
 * @copyright 2017 - aYaline Magento
 * @license   aYaline - http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 * @link      http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();

$contentFR = <<<HTML
<div class="rs">
<p>Suivez-nous</p>
<ul>
    <li><a href='https://www.facebook.com/pages/SABRE-PARIS/131241179309'><div class="fb"></div></a></li>
    <li><a href='https://twitter.com/SabreParis'> <div class="tw"></div></a></li>
    <li><a href="http://www.pinterest.com/sabreparis/"><div class="pn"></div></a></li>
</ul>
</div>
<div id="widget-container" class="ekomi-widget-container ekomi-widget-moderator582099f90063a">&nbsp;</div>
<script type="text/javascript">// <![CDATA[
    (function(w) {
        w['_ekomiServerUrl'] = (document.location.protocol=='https:'?'https:':'http:') + '//widgets.ekomi.com';
        w['_customerId'] = '106673';
        w['_ekomiDraftMode'] = true;
        w['_language']='fr';

        if(typeof(w['_ekomiWidgetTokens']) !== 'undefined'){
            w['_ekomiWidgetTokens'][w['_ekomiWidgetTokens'].length] = 'moderator582099f90063a';
                    } else {
            w['_ekomiWidgetTokens'] = new Array('moderator582099f90063a');
        }
        if(typeof(ekomiWidgetJs) == 'undefined') {
            var s = document.createElement('script');
            s.src = w['_ekomiServerUrl']+'/js/widget.js';
            s.async = true;
            var e = document.getElementsByTagName('script')[0];
            e.parentNode.insertBefore(s, e);
            ekomiWidgetJs = true;
        }
   })(window);
// ]]></script>
HTML;

Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load('reseaux-sociaux', 'identifier')
    ->setData('identifier', 'reseaux-sociaux')
    ->setData('content', $contentFR)
    ->setData('title', 'Réseaux sociaux Store FR')
    ->setData('stores', $frenchStoreId)
    ->setIsActive(1)
    ->save();

$contentEN = <<<HTML
<div class="rs">
<p>Suivez-nous</p>
<ul>
    <li><a href='https://www.facebook.com/pages/SABRE-PARIS/131241179309'><div class="fb"></div></a></li>
    <li><a href='https://twitter.com/SabreParis'> <div class="tw"></div></a></li>
    <li><a href="http://www.pinterest.com/sabreparis/"><div class="pn"></div></a></li>
</ul>
</div>
<div id="widget-container" class="ekomi-widget-container ekomi-widget-moderator582099f90063a">&nbsp;</div>
<script type="text/javascript">// <![CDATA[
    (function(w) {
        w['_ekomiServerUrl'] = (document.location.protocol=='https:'?'https:':'http:') + '//widgets.ekomi.com';
        w['_customerId'] = '106673';
        w['_ekomiDraftMode'] = true;
        w['_language']='fr';

        if(typeof(w['_ekomiWidgetTokens']) !== 'undefined'){
            w['_ekomiWidgetTokens'][w['_ekomiWidgetTokens'].length] = 'moderator582099f90063a';
                    } else {
            w['_ekomiWidgetTokens'] = new Array('moderator582099f90063a');
        }
        if(typeof(ekomiWidgetJs) == 'undefined') {
            var s = document.createElement('script');
            s.src = w['_ekomiServerUrl']+'/js/widget.js';
            s.async = true;
            var e = document.getElementsByTagName('script')[0];
            e.parentNode.insertBefore(s, e);
            ekomiWidgetJs = true;
        }
   })(window);
// ]]></script>
HTML;

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load('reseaux-sociaux', 'identifier')
    ->setData('identifier', 'reseaux-sociaux')
    ->setData('content', $contentEN)
    ->setData('title', 'Réseaux sociaux Store EN')
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save();