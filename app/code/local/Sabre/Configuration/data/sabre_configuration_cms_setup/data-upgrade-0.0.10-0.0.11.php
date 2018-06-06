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
$pageNotFoundEN = <<<HTML
<div class="mention conditions-page">
<h1>Page Not Found</h1>
<h2>The page you requested was not found :</h2>
<p>1- If you typed the URL directly, please make sure the spelling is correct.</p>
<p>2- If you clicked on a link to get here, the link is outdated.</p>
<div class="search404wrapper"><form id="search_mini_form_404" class="search404" action="{{store url='catalogsearch/result/'}}" method="get">
<div class="form-search"><img alt="" src="{{skin url='images/pictos/search.svg'}}" /><input id="search404" class="input-text" type="text" autocomplete="off" maxlength="128" value="" name="q" /><button class="button" title="search" type="submit"><span class="search404">ok</span></button>
<div id="search_autocomplete" class="search-autocomplete" style="display: none;">&nbsp;</div>
</div>
</form></div>
</div>
<div>{{widget type="catalog/product_widget_new" display_type="all_products" products_count="4" template="catalog/product/widget/new/content/new_grid.phtml"}}</div>
<div>{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="reassurance"}}</div>
HTML;

$pageNotFoundFR = <<<HTML
<div class="mention conditions-page">
<h1>Page introuvable</h1>
<h2>La page que vous avez demandé n'a pas été trouvée :</h2>
<p>1- Si vous avez tapé l'URL directement, s'il vous plaît assurez-vous que l'orthographe est correcte.</p>
<p>2- Si vous avez cliqué sur un lien pour arriver ici, le lien est désuet.</p>
<div class="search404wrapper"><form id="search_mini_form_404" class="search404" action="{{store url='catalogsearch/result/'}}" method="get">
<div class="form-search"><img alt="" src="{{skin url='images/pictos/search.svg'}}" /><input id="search404" class="input-text" type="text" autocomplete="off" maxlength="128" value="" name="q" /><button class="button" title="Chercher" type="submit"><span class="search404">ok</span></button>
<div id="search_autocomplete" class="search-autocomplete" style="display: none;">&nbsp;</div>
</div>
</form></div>
</div>
<div>{{widget type="catalog/product_widget_new" display_type="all_products" products_count="4" template="catalog/product/widget/new/content/new_grid.phtml"}}</div>
<div>{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="reassurance"}}</div>
HTML;



$PageNotFound = array('identifier'=>'no-route','title'=>'404 Not Found 1');

$_page = Mage::getModel('cms/page')->setStoreId($frenchStoreId)->load($PageNotFound['identifier'], 'identifier');
$_page->setData('identifier', $PageNotFound['identifier']);
$_page->setData('content', $pageNotFoundFR);
$_page->setData('title', $PageNotFound['title']);
$_page->setData('stores', [$frenchStoreId]);
$_page->setIsActive(1);
$_page->setRootTemplate("one_column");
$_page->save();

$_page = Mage::getModel('cms/page')->setStoreId($englishStoreId)->load($PageNotFound['identifier'], 'identifier');
$_page->setData('identifier', $PageNotFound['identifier']);
$_page->setData('content', $pageNotFoundEN);
$_page->setData('title', $PageNotFound['title']);
$_page->setData('stores', [$englishStoreId]);
$_page->setIsActive(1);
$_page->setRootTemplate("one_column");
$_page->save();