<?php
/**
 * Created by PhpStorm.
 * User: AAHD
 * Date: 11/10/2015
 * Time: 23:51
 */


$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();

$billboardType = Mage::getModel('ayalinebillboard/billboard_type')
                     ->load(Sabre_Billboard_Model_Billboard::LANDING_PAGE_IDENTIFIER, 'identifier');
if (!$billboardType->getId()) {
    $billboardType
        ->setData(array('identifier' => Sabre_Billboard_Model_Billboard::LANDING_PAGE_IDENTIFIER,
                        'title' => 'Landing page'))
        ->save();
}
$billboardTypeId = Mage::getModel('ayalinebillboard/billboard_type')
    ->load(Sabre_Billboard_Model_Billboard::LANDING_PAGE_IDENTIFIER, 'identifier')->getId();

//Dates
$now = date('Y-m-d H:i:s');
$limitDate = mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+22);
$limitDate = date("Y-m-d H:i:s",$limitDate);



$additional_content=
    <<<HTML

<div class="bloc1">
<img class="global" alt="image" src="{{skin url='images/top_img.jpg'}}" />
<img class="intern" alt="image" src="{{skin url='images/img_intern.png'}}" />
</div>
<div class="paragraph-1">
<h1>en ce moment</h1>
<h2>Sabre avec Akin & Suri</h2>
<p>
Akin & Suri est une marque basée à Londres, créée en 2008 par le designer textile Piyush Suri et l’architecte d’intérieur Burcu Akin.
Leur style unique est un mélange harmonieux de couleurs et de tons neutres, influencé par leurs pays d’origine que sont respectivement l’Inde et la
Turquie, et également bien sûr par leur vie multiculturelle dans la vibrante ville de Londres.
</p>
</div>
<div class="selection">
<div class="title">
<h2><span>La sélection</span>Akin & Suri</h2>
</div>
<div>{{widget type="catalog/product_widget_new" display_type="all_products" products_count="4" template="catalog/product/widget/new/content/new_grid.phtml"}}</div>
<div>{{widget type="catalog/product_widget_new" display_type="all_products" products_count="4" template="catalog/product/widget/new/content/new_grid.phtml"}}</div>
</div>
<div class="voir">
<div class="title">
<h2><span>a voir</span>également</h2>
</div>
</div>
<div>{{widget type="ayalinebillboard/widget_billboard_type" template="ayaline/billboard/widget/big.phtml" type_identifier="land_slider"}}</div>
<div class="paragraph-2">
<p>
 Texte à modifier, "SABRE" VIENT DU NOM ÉPONYME DU PREMIER COUVERT DÉVELOPPÉ PAR LA MARQUE.
ÉVOQUANT LA TRADITION, LE SAVOIR FAIRE, IL SYMBOLISE BIEN LA LAME QUI COUPE LES LIENS AVEC LE CONFORMISME.
Voilà un nom qui en dit long sur à cette marque ou se mêle chic et décalé. Les arts de la table ont bien changé, aujourd'hui on ose les mélanges, on se lasse des services uniques, on aime marier et mixer.
SABRE l'a bien compris et ce depuis maintenant 17 ans! En mettant à disposition de leur clientèle un grand nombres de motifs, et de couleurs, SABRE répond à un vrai besoin de liberté grâce à des combinaisons presque infinies.
Tout cela participe à créer ces collections gourmandes, aux couleurs vivantes et gaies. Un univers qui met tout le monde d'accord,
des plus fantaisistes aux plus sages, toutes s'y retrouvent.

</p>
<p>
Texte à modifier, LA FLEXIBILITÉ EST UNE NOTION IMPORTANTE CHEZ SABRE,
ON LA CULTIVE POUR LA PLUS GRANDE SATISFACTION DE LA CLIENTÈLE.
Leur valeur ajoutée c'est de pouvoir produire à la commande, et ainsi de permettre aux clients(es) de composer leur service sur mesure.
</p>
</div>
HTML;



// billboard page
$contents = [
    [
        'identifier' => 'landing-page',
        'additional_content' => $additional_content,
        'title' => 'Landing page'
    ]
];

foreach ($contents as $_content) {
    Mage::getModel('ayalinebillboard/billboard')
        ->setStoreId($frenchStoreId)
        ->load($_content['identifier'], 'identifier')
        ->setData('identifier', $_content['identifier'])
        ->setData('title', $_content['title'])
        ->setData('additional_content',$_content['additional_content'])
        ->setStores([$frenchStoreId])
        ->setTypes([$billboardTypeId])
        ->setCustomerGroupIds([Mage_Customer_Model_Group::NOT_LOGGED_IN_ID])
        ->setDisplayFrom($now)
        ->setDisplayTo($limitDate)
        ->setIsActive(1)
        ->save();

    Mage::getModel('ayalinebillboard/billboard')
        ->setStoreId($englishStoreId)
        ->load($_content['identifier'], 'identifier')
        ->setData('identifier', $_content['identifier'])
        ->setData('title', $_content['title'])
        ->setData('additional_content',$additional_content)
        ->setStores([$englishStoreId])
        ->setTypes([$billboardTypeId])
        ->setCustomerGroupIds([Mage_Customer_Model_Group::NOT_LOGGED_IN_ID])
        ->setDisplayFrom($now)
        ->setDisplayTo($limitDate)
        ->setIsActive(1)
        ->save();

}


/*
  billboard slide
*/

$billboardTypeSlider = Mage::getModel('ayalinebillboard/billboard_type')->load('land_slider','identifier')
    ->setIdentifier('land_slider')
    ->setTitle('land slider')
    ->save();
$billboardTypeIdSlider = $billboardTypeSlider->getId();


$slide1=
    <<<HTML
<div class="bill">
<div >
<div class="img">
<img  alt="image" src="{{skin url='images/noel.jpg'}}" />
</div>
<div class="span">
<span class="span1">préparez noël</span>
</div>
</div>
<div >
<div class="img">
<img alt="image" src="{{skin url='images/bonne.jpg'}}" />
</div>
<div class="span">
<span class="span1">bonne fête maman !</span>
</div>
</div>
<div >
<div class="img">
<img alt="image" src="{{skin url='images/boutique.jpg'}}" />
</div>
<div class="span">
<span class="span1">une boutique de plus</span>
</div>
</div>
</div>
HTML;
$slide2=
    <<<HTML
<div class="bill">
<div >
<div class="img">
<img  alt="image" src="{{skin url='images/noel.jpg'}}" />
</div>
<div class="span">
<span class="span1">préparez noël</span>
</div>
</div>
<div >
<div class="img">
<img alt="image" src="{{skin url='images/bonne.jpg'}}" />
</div>
<div class="span">
<span class="span1">bonne fête maman !</span>
</div>
</div>
<div >
<div class="img">
<img alt="image" src="{{skin url='images/boutique.jpg'}}" />
</div>
<div class="span">
<span class="span1">une boutique de plus</span>
</div>
</div>
</div>
HTML;

$_slides=[
    [
        'identifier'=>'land_slide_1',
        'content'=>$slide1,
        'title'=>'landing slide 1'
    ],
    [
        'identifier'=>'land_slide_2',
        'content'=>$slide2,
        'title'=>'landing slide 2'
    ]

];
foreach($_slides as $_slide) {
    Mage::getModel('ayalinebillboard/billboard')
        ->setStoreId($frenchStoreId)
        ->load($_slide['identifier'], 'identifier')
        ->setData('identifier', $_slide['identifier'])
        ->setData('title', $_slide['title'])
        ->setData('content', $_slide['content'])
        ->setCustomerGroupIds([Mage_Customer_Model_Group::NOT_LOGGED_IN_ID])
        ->setDisplayFrom($now)
        ->setDisplayTo($limitDate)
        ->setStores([$frenchStoreId])
        ->setTypes([$billboardTypeIdSlider])
        ->setIsActive(1)
        ->save();

    Mage::getModel('ayalinebillboard/billboard')
        ->setStoreId($englishStoreId)
        ->load($_slide['identifier'], 'identifier')
        ->setData('identifier', $_slide['identifier'])
        ->setData('title', $_slide['title'])
        ->setData('content', $_slide['content'])
        ->setCustomerGroupIds([Mage_Customer_Model_Group::NOT_LOGGED_IN_ID])
        ->setDisplayFrom($now)
        ->setDisplayTo($limitDate)
        ->setStores([$englishStoreId])
        ->setTypes([$billboardTypeIdSlider])
        ->setIsActive(1)
        ->save();
}

#212627
/*
*/
