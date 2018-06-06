<?php
/**
 * Created by PhpStorm.
 * User: AAHD
 * Date: 02/10/2015
 * Time: 11:26
 */

$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();


//block Réassurance
$reassurance=
    <<<HTML
<div class="reasurance">
<div>
    <div class="item"><a><img alt="" src="{{skin url='images/pictos/r_cmd.svg'}}" />
    <h3>Commande<span>exp&eacute;di&eacute;e &agrave; J+1</span></h3></a>
    </div>
    <div class="item"><a> <img alt="" src="{{skin url='images/pictos/r_livraison.svg'}}"  />
    <h3>Livraison offerte<span>d&egrave;s 100 &euro; d&rsquo;achat</span></h3></a>
    </div>
    <div class="item"><a> <img alt="" src="{{skin url='images/pictos/r_conseils.svg'}}" />
    <h3>Conseillers et SAV<span> &agrave; votre service </span></h3></a>
    </div>
    <div class="item"><a> <img alt="" src="{{skin url='images/pictos/r_cadeau.svg'}}" />
    <h3>Emballages<span>cadeaux</span></h3></a>
    </div>
    <div class="item"><a> <img alt="" src="{{skin url='images/pictos/r_paiement.svg'}}" />
    <ul>
    <li><img alt="" src="{{skin url='images/pictos/r_cb.svg'}}" /></li>
    <li><img alt="" src="{{skin url='images/pictos/r_visa.svg'}}" /></li>
    <li><img alt="" src="{{skin url='images/pictos/r_mc.svg'}}" /></li>
    <li><img alt="" src="{{skin url='images/pictos/r_paypal.svg'}}" /></li>
    </ul>
    <h3>Paiement s&eacute;curis&eacute;</h3></a>
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

// Home page

$homeTitle_fr = "Home Store FR";
$homeTitle_en = "Home Store EN";
$homeIdentifier="home";

$homeCntent_fr=
    <<<HTML
<div>{{widget type="ayalinebillboard/widget_billboard_type" template="ayaline/billboard/widget/big.phtml" type_identifier="home_slider"}}</div>
<div class="list-categoris">{{block type="catalog/navigation" name="catalog.category" template="catalog/category/list.phtml"}}</div>
<div class="selection">
<div class="title">
<h2><span>La</span> sélection du moment</h2>
</div>
<div>{{widget type="catalog/product_widget_new" display_type="all_products" products_count="5" template="catalog/product/widget/new/content/new_grid.phtml"}}</div>
<div>{{widget type="sabre_catalog/product_widget_skuFilter" template="sabre/catalog/product/widget/skufilter/content/products_grid.phtml" products_sku="produit-23,22,66,85"}}</div>
</div>
<div>{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="reassurance"}}</div>
<div class="sabre">
<div class="bloc esprit">{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="esprit-sabre"}}</div>
<div class="bloc boutique">{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="boutiques"}}</div>
</div>
<div class="description">{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="descriptions"}}</div>
HTML;

$homeCntent_en=
    <<<HTML
<div>{{widget type="ayalinebillboard/widget_billboard_type" template="ayaline/billboard/widget/big.phtml" type_identifier="home_slider"}}</div>
<div class="list-categoris">{{block type="catalog/navigation" name="catalog.category" template="catalog/category/list.phtml"}}</div>
<div class="selection">
<div class="title">
<h2><span>The</span> selection of the moment</h2>
</div>
<div>{{widget type="catalog/product_widget_new" display_type="all_products" products_count="5" template="catalog/product/widget/new/content/new_grid.phtml"}}</div>
<div>{{widget type="sabre_catalog/product_widget_skuFilter" template="sabre/catalog/product/widget/skufilter/content/products_grid.phtml" products_sku="produit-23,22,66,85"}}</div>
</div>
<div>{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="reassurance"}}</div>
<div class="sabre">
<div class="bloc esprit">{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="esprit-sabre"}}</div>
<div class="bloc boutique">{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="boutiques"}}</div>
</div>
<div class="description">{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="descriptions"}}</div>
HTML;

Mage::getModel('cms/page')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setTitle($homeTitle_fr)
    ->setIdentifier($homeIdentifier)
    ->setContent($homeCntent_fr)
    ->setIsActive(1)
    ->setStores($frenchStoreId)
    ->setRootTemplate("one_column")
    ->save();

Mage::getModel('cms/page')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setTitle($homeTitle_en)
    ->setIdentifier($homeIdentifier)
    ->setContent($homeCntent_en)
    ->setIsActive(1)
    ->setStores($englishStoreId)
    ->setRootTemplate("one_column")
    ->save();





