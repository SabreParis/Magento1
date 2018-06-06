<?php
$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();


//=========Couverts==================//


//>>>>>>>>>>model-aquarelle
$homeIdentifier="model-aquarelle";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-aquarelle')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-aquarelle')
    ->save();


//>>>>>>>>>>BRETON
$homeIdentifier="model-breton";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-breton')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-breton')
    ->save();

//>>>>>>>>>>gustave
$homeIdentifier="model-gustave";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-gustave')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-gustave')
    ->save();

//>>>>>>>>>>Leon
$homeIdentifier="model-leon";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-leon')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-leon')
    ->save();


//>>>>>>>>>>Natura
$homeIdentifier="model-natura";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-natura')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-natura')
    ->save();

//>>>>>>>>>>pois-blancs

$homeIdentifier="model-pois-blancs";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-pois-blancs')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-pois-blancs')
    ->save();

//>>>>>>>>>>tartan
$homeIdentifier="model-tartan";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-tartan')
    ->save();
Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-tartan')
    ->save();

//>>>>>>>>>>tulipe
$homeIdentifier="model-tulipe";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-tulipe')
    ->save();
Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-tulipe')
    ->save();

//>>>>>>>>>>baguette
$homeIdentifier="model-baguette";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-baguette')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-baguette')
    ->save();

//>>>>>>>>>>djembe
$homeIdentifier="model-djembe";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-djembe')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-djembe')
    ->save();


//>>>>>>>>>> hibiscus
$homeIdentifier="model-hibiscus";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-hibiscus')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-hibiscus')
    ->save();


//>>>>>>>>>>Leopard
$homeIdentifier="model-leopard";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-leopard')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-leopard')
    ->save();


//>>>>>>>>>>Nature
$homeIdentifier="model-nature";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-nature')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-nature')
    ->save();


//>>>>>>>>>>tiare
$homeIdentifier="model-tiare";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-tiare')
    ->save();
Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-tiare')
    ->save();

//>>>>>>>>>>vichy
$homeIdentifier="model-vichy";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-vichy')
    ->save();
Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-vichy')
    ->save();

//>>>>>>>>>>bambou
$homeIdentifier="model-bambou";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-bambou')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-bambou')
    ->save();

//>>>>>>>>>>dumbo
$homeIdentifier="model-dumbo";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-dumbo')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-dumbo')
    ->save();

//>>>>>>>>>>katmandou
$homeIdentifier="model-katmandou";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-katmandou')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-katmandou')
    ->save();

//>>>>>>>>>>marguerite
$homeIdentifier="model-marguerite";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-marguerite')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-marguerite')
    ->save();

//>>>>>>>>>>paquerette
$homeIdentifier="model-paquerette";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-paquerette')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-paquerette')
    ->save();

//
////>>>>>>>>>>Provençal
//$homeIdentifier="model-Provençal";
//Mage::getModel('cms/block')->load($homeIdentifier,'identifier')
//    ->setIdentifier('model-cutlery-provençal')
//    ->save();

//>>>>>>>>>>tortue

$homeIdentifier="model-tortue";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-tortue')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-tortue')
    ->save();


//>>>>>>>>>>vintage
$homeIdentifier="model-vintage";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-vintage')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-vintage')
    ->save();

//>>>>>>>>>>basic
$homeIdentifier="model-basic";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-basic')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-basic')
    ->save();

//>>>>>>>>>>monaco
$homeIdentifier="model-monaco";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-monaco')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-monaco')
    ->save();

//>>>>>>>>>>paris
$homeIdentifier="model-paris";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-paris')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-paris')
    ->save();

//>>>>>>>>>>pure
$homeIdentifier="model-pure";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-pure')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-pure')
    ->save();

//>>>>>>>>>>transat
$homeIdentifier="model-transat";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-transat')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-transat')
    ->save();

//>>>>>>>>>>zebre
$homeIdentifier="model-zebre";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-zebre')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-zebre')
    ->save();

//=========Porcelaine=================//

//>>>>>>>>>>pois-blancs

$_block = Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load('model-porcelain-pois-blancs', 'identifier');
$_block->setData('identifier','model-porcelain-pois-blancs');
$_block->setData('content', '');
$_block->setData('title', 'pois');
$_block->setData('stores', $frenchStoreId);
$_block->setIsActive(1);
$_block->save();


$_block = Mage::getModel('cms/block')->setStoreId($englishStoreId)->load('model-porcelain-pois-blancs', 'identifier');
$_block->setData('identifier', 'model-porcelain-pois-blancs');
$_block->setData('content', '');
$_block->setData('title', 'pois');
$_block->setData('stores', $englishStoreId);
$_block->setIsActive(1);
$_block->save();


//>>>>>>>>>>MEGEVE

$_block = Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load('model-porcelain-megeve', 'identifier');
$_block->setData('identifier','model-porcelain-megeve');
$_block->setData('content', '');
$_block->setData('title', 'megeve');
$_block->setData('stores', $frenchStoreId);
$_block->setIsActive(1);
$_block->save();


$_block = Mage::getModel('cms/block')->setStoreId($englishStoreId)->load('model-porcelain-megeve', 'identifier');
$_block->setData('identifier', 'model-porcelain-megeve');
$_block->setData('content', '');
$_block->setData('title', 'megeve');
$_block->setData('stores', $englishStoreId);
$_block->setIsActive(1);
$_block->save();

//>>>>>>>>>>numero1
$contentNumero1 = <<<HTML
<div class="bloc center"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/PORCELAINE/Numero_1/img-Numero1-GM.png"}}" /> <span>numero1</span></div>
<div class="bloc right"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/PORCELAINE/Numero_1/img-Numero1-PM.png"}}" /></div>
<div class="bloc left"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/PORCELAINE/Numero_1/img-numero1-MM.png"}}" /></div>
HTML;

$homeIdentifier="model-numero1";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-porcelain-numero-1')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-porcelain-numero-1')
    ->setData('content', $contentNumero1)
    ->setData('title', 'numero1')
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save();

//>>>>>>>>>>>derin
$contentDerin = <<<HTML
<div class="bloc center"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/PORCELAINE/Derin/img-Derin-GM.png"}}" /> <span>derin</span></div>
<div class="bloc right"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/PORCELAINE/Derin/img-Derin-PM.png"}}" /></div>
<div class="bloc left"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/PORCELAINE/Derin/img-Derin-MM.png"}}" /></div>
HTML;

$homeIdentifier="model-derin";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-porcelain-derin')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-porcelain-derin')
    ->setData('content', $contentDerin)
    ->setData('title', 'derin')
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save();

////>>>>>>>>>>selin
$contentSelin = <<<HTML
<div class="bloc center"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/PORCELAINE/Selin/img-Selin-GM.png"}}" /> <span>selin</span></div>
<div class="bloc right"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/PORCELAINE/Selin/img-Selin-PM.png"}}" /></div>
<div class="bloc left"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/PORCELAINE/Selin/img-Selin-MM.jpg"}}" /></div>
HTML;
$homeIdentifier="model-selin";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-porcelain-selin')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-porcelain-selin')
    ->setData('content', $contentSelin)
    ->setData('title', 'selin')
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save();

//===================================Accessory====================

////>>>>>>>>>>bucolic-fleurs


$contentFleurs = <<<HTML
<div class="bloc center"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Bucolic_Fleurs/img-BucolicFleurs-GM02.png"}}" /> <span>fleurs</span></div>
<div class="bloc right"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Bucolic_Fleurs/img-BucolicFleurs-PM.png"}}" /></div>
<div class="bloc left"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Bucolic_Fleurs/img-BucolicFleurs-MM.png"}}" /></div>
HTML;
$homeIdentifier="model-bucolic-fleurs";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-bucolic-fleurs')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-bucolic-fleurs')
    ->setData('content', $contentFleurs)
    ->setData('title', 'Bucolic-Fleurs')
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save();


////>>>>>>>>>>bucolic-papillons

$contentPapillons = <<<HTML
<div class="bloc center"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Bucolic_Papillon/img-BucolicPapillon-GM.png"}}" /> <span>papillon</span></div>
<div class="bloc right"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Bucolic_Papillon/img-BucolicPapillon-PM.png"}}" /></div>
<div class="bloc left"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Bucolic_Papillon/img-BucolicPapillon-MM.png"}}" /></div>
HTML;

$homeIdentifier="model-bucolic-papillons";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-bucolic-papillons')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-bucolic-papillons')
    ->setData('content', $contentPapillons)
    ->setData('title', 'Bucolic-Papillon')
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save();


////>>>>>>>>>>bucolic-piou-piou

$contentPiou = <<<HTML
<div class="bloc center"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Bucolic_Pioupiou/img-BucolicPioupiou-GM.png"}}" /> <span>pioupiou</span></div>
<div class="bloc right"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Bucolic_Pioupiou/img-BucolicPioupiou-PM.png"}}" /></div>
<div class="bloc left"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Bucolic_Pioupiou/img-BucolicPioupiou-MM.png"}}" /></div>
HTML;
$homeIdentifier="model-bucolic-piou-piou";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-bucolic-piou-piou')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-bucolic-piou-piou')
    ->setData('content', $contentPiou)
    ->setData('title', 'Bucolic-pioupiou')
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save();

//>>>>>>>>>>model-charme-liberty

$contentLiberty = <<<HTML
<div class="bloc center"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Charme_Liberty/img-CharmeLiberty-GM.png"}}" /> <span>liberty</span></div>
<div class="bloc right"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/PORCELAINE/Pois_1/img-Pois-PM.png"}}" /></div>
<div class="bloc left"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Charme_Liberty/img-CharmeLiberty-MM.png"}}" /></div>
HTML;
$homeIdentifier="model-charme-liberty";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-charme-liberty')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-charme-liberty')
    ->setData('content', $contentLiberty)
    ->setData('title', 'Charme Liberty')
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save();

//charme-pois

$contentPois = <<<HTML
<div class="bloc center"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/CHARME_POIS/img-CharmePois-GM.png"}}" /> <span>pois</span></div>
<div class="bloc right"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/CHARME_POIS/img-CharmePois-PM.png"}}" /></div>
<div class="bloc left"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/CHARME_POIS/img-CharmePois-MM.png"}}" /></div>
HTML;
$homeIdentifier="model-charme-pois";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-charme-pois')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-charme-pois')
    ->setData('content', $contentPois)
    ->setData('title', 'Charme Pois')
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save()
    ->save();

//>>>>>>>>>>charme-vichy

$contentVichy = <<<HTML
<div class="bloc center"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Charme_Vichy/img-CharmeVichy-GM_1.png"}}" /> <span>vichy</span></div>
<div class="bloc right"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Charme_Vichy/img-CharmeVichy-PM.png"}}" /></div>
<div class="bloc left"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Charme_Vichy/img-CharmeVichy-MM.png"}}" /></div>
HTML;
$homeIdentifier="model-charme-vichy";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-charme-vichy')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-charme-vichy')
    ->setData('content', $contentVichy)
    ->setData('title', 'Charme Vichy')
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save();

//>>>>>>>>>>ice
$contentIce = <<<HTML
<div class="bloc center"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Ice/img-Ice-GM.png"}}" /> <span>ice</span></div>
<div class="bloc right"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Ice/img-Ice-PM.png"}}" /></div>
<div class="bloc left"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Ice/img-Ice-MM.png"}}" /></div>
HTML;
$homeIdentifier="model-ice";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-ice')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-ice')
    ->setData('content', $contentIce)
    ->setData('title', 'Ice')
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save();


//>>>>>>>>>>old fashion
$contentOld = <<<HTML
<div class="bloc center"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Old_Fashion/img-OldFashion-GM.png"}}" />
<span>oldfashion</span></div>
<div class="bloc right"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Old_Fashion/img-OldFashion-PM.png"}}" /></div>
<div class="bloc left"><img alt="" src="{{media url="wysiwyg/Fiche_Produit/ACCESSOIRES/Old_Fashion/img-OldFashion-MM.png"}}" /></div>
HTML;
$homeIdentifier="model-old-fashion";
Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($homeIdentifier,'identifier')
    ->setIdentifier('model-accessory-old-fashion')
    ->save();

Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($homeIdentifier,'identifier')
    ->setData('content', '')
    ->setData('title', $contentOld)
    ->setData('stores', $englishStoreId)
    ->setIsActive(1)
    ->save();

//>>>>>>>>>>courchevel création de block
$_block = Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load('model-accessory-courchevel', 'identifier');
$_block->setData('identifier','model-accessory-courchevel');
$_block->setData('content', '');
$_block->setData('title', 'courchevel');
$_block->setData('stores', $frenchStoreId);
$_block->setIsActive(1);
$_block->save();


$_block = Mage::getModel('cms/block')->setStoreId($englishStoreId)->load('model-accessory-courchevel', 'identifier');
$_block->setData('identifier', 'model-accessory-courchevel');
$_block->setData('content', '');
$_block->setData('title', 'courchevel');
$_block->setData('stores', $englishStoreId);
$_block->setIsActive(1);
$_block->save();