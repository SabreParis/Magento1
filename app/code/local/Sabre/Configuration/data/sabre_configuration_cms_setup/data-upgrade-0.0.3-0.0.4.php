<?php
/**
 * Created by PhpStorm.
 * User: AAHD
 * Date: 02/10/2015
 * Time: 11:26
 */

$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();


// page mention legale
$mentionContent =
    <<<HTML
<div class="mention">
<h1>MENTIONS-LEGALES</h1>
<h2>Droits d'auteurs, textes, photos, liens, copyright &copy;</h2>
<p>
 Le site <a>www.sabre.fr</a> est la propriété de la société SABRE. L'ensemble du site relève de la législation française et internationale sur le droit
 d'auteur et la propriété intellectuelle. Toute représentation, utilisation ou reproduction (dans sa forme ou son contenu) du site <a>www.sabre.fr</a> est
 strictement interdite sans autorisation écrite préalable de la société SABRE. Elle sera en tout état de cause soumise à l'obligation de faire apparaître la
 mention claire et lisible de l'adresse du site :<a>www.sabre.fr</a>. Toutes images, textes et éléments graphiques utilisés sans le consentement de la société
 SABRE pourront faire l'objet de poursuites légales.
  </p>
<h2>Avertissement</h2>
<p>
Les données mises en ligne sur le site Internet ont pour objectif de présenter une des activités de la société SABRE. Leur exactitude est périodiquement
contrôlée et leur mise à jour assurée régulièrement. Des erreurs ou omissions indépendantes de la volonté de la société SABRE peuvent toutefois se glis-
ser dans les pages de son site <a>www.sabre.fr</a>. En tout état de cause, la responsabilité de la société SABRE ne saurait être retenue en cas de préjudice direct
ou indirect (notamment tout préjudice financier, commercial, perte de programme ou de donnée) résultant de l'usage de son site et de l'utilisation des
données et informations qui y sont mises en ligne. La société SABRE décline également toute responsabilité à l'égard de tout dommage lié à l'utilisation
des liens hypertextes mise en ligne sur son site et à la consultation des sites auxquels ils renvoient. Il est expressément rappelé que la société SABRE n'a
aucun contrôle ni aucune responsabilité sur le contenu de ces sites. Il incombe donc à chaque internaute de prendre les précautions nécessaires afin de
s'assurer que le site sélectionné n'est pas infesté de virus ou autre parasite de nature destructive.
 </p>
<h2>Informations nominatives</h2>
<p>
Le site <a>www.sabre.fr</a>, qui comporte des informations nominatives concernant ses partenaires, a fait l'objet d'une déclaration auprès de la CNIL. Les infor-
mations recueillies par le biais de nos formulaires de contact et de newsletter n'ont d'autre but que de renseigner et de pouvoir répondre aux interroga-
tions des internautes.Aucune information personnelle n'est cédée à des tiers. Conformément à la loi &laquo; informatique et libertés &raquo; du 6 janvier 1978, les
internautes bénéficient d'un droit d'accès et de rectification aux informations qui les concernent. Si vous souhaitez exercer ce droit et obtenir communi-
cation ou suppression des informations vous concernant (vous pouvez, pour des motifs légitimes, vous opposer au traitement des données vous
 concernant), veuillez contacter la société SABRE.
</p>
<h2>Cookie</h2>
<p>
L'utilisateur est informé que lors de ses visites sur le site, un cookie peut s'installer automatiquement sur son logiciel de navigation (un cookie est un
bloc de données qui ne permet pas de l'identifier mais qui sert à enregistrer des informations relatives à la navigation de celui-ci sur le site).
En général, ce cookie contient, un numéro d'identification ou un code qui nous permet de vous reconnaî;tre lorsque vous retournez sur notre site.
La mise en place de ces systèmes nous permet simplement la récupération de données statistiques afin d'améliorer notre site et de mieux répondre à
vos besoins.Les données concernées ne sont en aucun cas cédés à des tiers. Nous vous rappelons que votre navigateur possède des fonctionnalités
qui vous permettent de vous opposer à l'enregistrement de cookies, d'être prévenu avant d'accepter les cookies, ou de les effacer.
Pour plus d'information sur les cookies ainsi que sur les différentes prescriptions décrites plus haut, nous vous recommandons de visiter le site Internet
de la CNIL: <a>www.cnil.fr</a>. Ce site Web utilise la technologie etracker (<a>www.etracker.com</a>) pour collecter des renseignement sur le comportement des visi-
teurs. Ces données sont collectées de manière totalement anonyme et uniquement à des fins de marketing et d'optimisation du site. Elles sont associées
à un identifiant d'utilisateur anonyme, puis enregistrées afin de pouvoir créer des profils d'utilisation. Les cookies peuvent être utilisés pour collecter et
enregistrer ces données, mais celles-ci restent toujours anonymes. Ces données ne seront pas utilisées pour identifier personnellement un utilisateur
et ne sont croisées avec aucune donnée personnelle.</br>
Il est possible de refuser à tout moment la collecte et le stockage de ces données et l'utilisation des services associés.
</p>
<h2>Crédits</h2>
<p>
Raison sociale : SABRE SAS</br>
Hébergement du site : <span>HOSTPARTNER</span></br>
Réalisation du site : <span>AYALINE</span>
</p>
</div>
HTML;

// page marque

$mentionContent1 =
    <<<HTML
<div class="page">
<div id="collection">
<div class="bloc iframe1">
<iframe width="100%" height="100%" frameborder="0" src="https://www.youtube.com/v/watch?v=XLQHAb_wAeY"></iframe>
<div class="text">
<div class="position">
<p class="title">le savoir-faire</p>
<p class="content">Atelier de fabrication de Chatou</p>
</div>
</div>
</div>
<div class="bloc width75">
<img src="{{skin url='images/fourchette1.jpg'}}" alt="fourchette"   />
<div class="text">
<div class="position">
<p class="title">natura</p>
</div>
</div>
<a href="#"></a>
</div>
<div class="bloc width25">
<img src="{{skin url='images/marie_claire.jpg'}}" alt="marie_claire" /*width="100%" height="256" */ />
<div class="text">
<div class="position">
<p class="title">la presse</p>
<p class="content">en parle...</p>
</div>
</div>
<a href="#"></a>
</div>
<div class="bloc width50">
<img src="{{skin url='images/une_fourchette.jpg'}}" alt="fourchette" /* width="100%" height="256"*/  />
<div class="text">
<div class="position">
<p class="title">bambou</p>
</div>
</div>
<a href="#"></a>
</div>
<div class="bloc width25">
<img src="{{skin url='images/fem_hom.jpg'}}" alt="femme_homme" /*width="100%" height="256"*/  />
<div class="text">
<div class="position">
<p class="title">l'histoire</p>
<p class="content">Comment tout à commencé...</p>
</div>
</div>
<a href="#"></a>
</div>
<div class="bloc width25">
<img src="{{skin url='images/ensemble.jpg'}}" alt="ensemble" /*width="100%" height="256"*/  />
<div class="text">
<div class="position">
<p class="title">vintage</p>
</div>
</div>
<a href="#"></a>
</div>
<div class="bloc iframe2">
<iframe width="100%" height="100%" frameborder="0" src="https://www.youtube.com/v/watch?v=XLQHAb_wAeY"></iframe>
<div class="text">
<div class="position">
<p class="title">lookbook</p>
</div>
</div>
</div>
<div class="bloc width25">
<img src="{{skin url='images/catalogue.jpg'}}" alt="ensemble" /*width="100%" height="256"*/  />
<div class="text">
<div class="position">
<p class="title">catalogue</p>
<p class="content">Automne-Hivers 2015</p>
</div>
</div>
<a href="#"></a>
</div>
<div class="bloc width25">
<img src="{{skin url='images/taxi.jpg'}}" alt="ensemble" /*width="100%" height="256"*/  />
<div class="text">
<div class="position">
<p class="title">@sabre - new york summer 2015</p>
</div>
</div>
<a href="#"></a>
<a class="face" href="#"><img    src="{{skin url='images/FACEBOOK.png'}}" alt="face"  /*width="100%" height="256"*/  /></a>
</div>
<div class="bloc width25">
<img src="{{skin url='images/home_1.jpg'}}" alt="ensemble" /*width="100%" height="256"*/  />
<div class="text">
<div class="position">
<p class="title">la presse</p>
<p class="content">en parle</p>
</div>
</div>
<a href="#"></a>
</div>
<div class="bloc width25">
<img src="{{skin url='images/collection.jpg'}}" alt="ensemble" /*width="100%" height="256"*/ />
<div class="text">
<div class="position">
<p class="title">@sabre</p>
</div>
</div>
<a href="#"></a>
</div>
</div>
<div class="paragraphe">
<p>
Texte à modifier, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.
Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi.
Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper.
</p>
</div>
</div>
HTML;

//page livraison
$mentionContent2=
    <<<HTML
<div class="mention">
<h1>LIVRAISON</h1>
<p>
La boutique SABRE en ligne livre ses produits en France métropolitaine (Corse comprise) ainsi que Italie, Suisse , Espagne, Grande Bretagne, Grèce et Irlande.
Les articles seront expédiés à l’adresse de livraison indiqués par le client lors de sa commande.
UPS assure la livraison en 24h/48h/72h (suivant les pays et les régions )
Une fois la commande prise en compte, elle est traitée par notre service logistique. Vous serez averti dès le départ de votre commande par un email qu’UPS vous aura transmis sur l’adresse email que vous nous avez communiqué lors de votre commande, accompagné du numéro de suivi de votre colis.
Des réceptions de votre numéro de suivi, votre colis vous parviendra sous le délai indiqué par UPS.
En cas d'absence du client lors de la livraison, un avis de passage sera déposé dans sa boite aux lettres l'informant du passage du service de livraison et l'invitant à prendre contact avec UPS. UPS se donne le droit de déposer la commande à un gardien, voisins… si la personne n’est pas la pour réceptionner.
</p>
<h2>Garantie satisfait ou remboursé</h2>
<p>
Vous disposez d’un délai de quinze jours à compter de la date d’expédition pour retourner le ou les articles et obtenir les remboursements des sommes déjà versées.
L’article devra être retourné à l’état neuf, dans son emballage d’origine et accompagné de son bon de livraison et d’un mot qui explique la raison de son retour. SABRE se donne le droit de refuser le retour si la raison est non justifiée.
 </p>
<h2>Retours gratuits</h2>
<p>
Si vous souhaitez renvoyer votre commande ou certains des articles qu'elle contient, veuillez nous contacter directement. Veillez à fournir les informations suivantes:
</p>
<ul>
<li>&nbsp;&nbsp;&nbsp;&nbsp; -&nbsp;&nbsp;&nbsp; votre numéro de commande Web </li>
<li>&nbsp;&nbsp;&nbsp;&nbsp; -&nbsp;&nbsp;&nbsp; l'adresse complète où nous pouvons récupérer la marchandise </li>
<li>&nbsp;&nbsp;&nbsp;&nbsp; -&nbsp;&nbsp;&nbsp; un numéro de téléphone où vous contacter </li>
<li>&nbsp;&nbsp;&nbsp;&nbsp; -&nbsp;&nbsp;&nbsp; une plage horaire pendant laquelle nos transporteurs peuvent se présenter</li>
</ul>
</div>
HTML;

//page paiment securise
$mentionContent3=
    <<<HTML
<div class="mention">
<h1>PAIEMENT SÉCURISÉ</h1>
<p>
Les solutions de paiements adoptés par SABRE E-BOUTIQUE sont 100% sécurisées.
Pour les paiements par carte bancaire (carte bleue visa, Mastercard ou AMERICAN EXPRESS et e-carte bleue), le Crédit Mutuel CYBERMUT se charge de la sécurisation des paiements.
Toutes les informations que les clients communiquent à SABRE E-BOUTIQUE sont strictement protégées et garantissent la conformité et la sécurisation de chaque transaction.
SABRE E-BOUTIQUE vous offre également le choix de régler vos achats par Paypal ou par chèques adressé à l’ordre de:
</p>
<ol>
<li>SABRE</li>
<li>21 avenue de l’Europe</li>
<li>78400 CHATOU</li>
<li>FRANCE</li>
</ol>
<p>Vous pouvez contacter notre service client du lundi au vendredi de 8h à 17h au 01 30 09 50 00</p>
</div>
HTML;

//page service client

$mentionContent4=
    <<<HTML
<div class="mention">
<ul class="list-service">
<li>Par mail :</li>
<li><a href="mailto: contact@sabre.fr">contact@sabre.fr</a></li>
<li>Par courrier :</li>
<li class="line-none">21 avenue de l’Europe 78400 CHATOU</li>
<li>Par téléphone :</li>
<li class="line-none">+33 (0)1 30 09 50 00</li>
</ul>
<p>Du lundi au vendredi de 8h-12h et 13h-17h</p>
<p>Pour procéder à un échange ou un remboursement, il vous suffit de nous réexpédier les articles par <span">poste suivie,</span>
accompagnés de la référence de votre commande (bon de livraison ou facture) et de votre liste d’échange souhaités ou de votre souhait d’être rembourser. Nous reviendrons vers vous en cas de différence de prix entre les références retournées et celles échangées.
</p>
<p>Notre adresse :</p>
<ol>
<li>SABRE</li>
<li>Att. Alice</li>
<li>21 Avenue de l’Europe</li>
<li>78400 CHATOU</li>
</ol>
</div>
HTML;

// page conditions generales vente
$mentionContent5=
    <<<HTML
<div class="mention conditions-page">
<h1>CONDITIONS GÉNÉRALES DE VENTES</h1>
<ul>
<li>SABRE PARIS SAS</li>
<li>Capital social de 1 050 000 euros.</li>
<li>Siège social est 21 avenue de l'Europe, 78400 Chatou</li>
<li>Téléphone 01 30 09 50 00 Fax: 01 30 09 50 01</li>
<li>ID TVA: FR 31 390 649 044</li>
<li>Immatriculé au registre du commerce RCS Versailles 2001B01148</li>
</ul>
<p>
Sabre Paris SAS a une activité de fabrication montage et négoce d'article d'équipement du foyer et propose un service de vente à distance des articles sus mentionnés
(ci après dénommés ""article(s)"" via son site" boutique.sabre.fr (ci après dénommés " Boutique en ligne ") L'offre et la vente d'articles sur la Boutique en ligne au client,
défini comme l'utilisateur de la " Boutique en ligne ", sont régies par les Conditions Générales de Vente énoncées ci-après qui précisent, notamment, les conditions de
commande, de paiement, de livraison et de gestion des éventuels retours des articles commandés. Ces Conditions Générales de Vente peuvent être consultées simplement, librement
et à tout moment en cliquant sur le lien "Conditions de Vente". Pour toute information, le client peut contacter le service d'assistance de SABRE en cliquant sur le lien
"Contact". Enfin le client peut contacter SABRE en permanence par e-mail info@sabre.fr ou par téléphone au numéro 01 30 09 50 00. Pour toute autre information légale, merci de
consulter les espaces : Droit de Rétractation et Politique de Confidentialité.
</p>
<h2>1. POLITIQUE COMMERCIALE</h2>
<ol>
<li>1.1 SABRE met à la vente directement sur la " Boutique en ligne " des articles et pratique sa propre activité de commerce électronique exclusivement à l'égard d'utilisateurs finaux que sont les clients consommateurs.</li>
<li>1.2 Par "consommateur" SABRE désigne toute personne physique capable, ayant atteint la majorité légale et non soumise à un régime de tutelle ou de curatelle, qui opère à des fins étrangères à toute activité d'entreprise ou professionnelle.</li>
<li>1.3 Compte tenu de la politique commerciale décrite ci-dessus, SABRE se réserve le droit de ne pas donner suite aux commandes provenant de personnes qui ne répondent pas à la définition donnée de consommateur ou à des commandes non-conformes à sa politique commerciale.</li>
<li>1.4 Ces conditions Générales de Vente régissent exclusivement l'offre et l'acceptation des ordres d'achat de produits ainsi que le paiement, la livraison et les éventuels retours des articles commandés par le client à SABRE sur sa " Boutique en ligne ".</li>
<li>1.5 En choisissant d'acheter des articles de SABRE sur " Boutique en ligne " le client accepte expressément et irrévocablement les termes des Conditions Générales de Vente de SABRE.</li>
<li>1.6 Ces Conditions Générales de Vente prévaudront sur toutes autres conditions générales ou particulières non expressément agréées par SABRE.</li>
<li>1.7 SABRE se réserve le droit de pouvoir modifier ses Conditions Générales de Vente. Les conditions applicables seront celles en vigueur à la date de la commande par le client.</li>
</ol>
<h2>2. CARACTERISTIQUES DES ARTICLES PROPOSES</h2>
<ol>
<li>2.1 Les articles proposés sont ceux qui figurent sur la " Boutique en ligne " SABRE.</li>
<li>2.2 Ces articles sont proposés dans la limite des stocks disponibles</li>
<li>2.3 Les photographies et descriptifs des articles sont les plus fidèles possibles, mais ne peuvent assurer une similitude parfaite avec l'article proposé, notamment en ce qui concerne les couleurs. Toutes dissemblances ou erreurs, notamment techniques ou typographiques, qui s'y seraient introduites, ne pourront en aucun cas engager la responsabilité de SABRE et donner droit revendication de la part du client.</li>
</ol>
<h2>3. MODE DE PRISE DE COMMANDE</h2>
<ol>
<li>3.1 La langue disponible pour conclure le contrat est le français.</li>
<li>
3.2 Pour conclure un contrat d'achat d'un ou plusieurs articles sur la " Boutique en ligne ", le client doit obligatoirement:
<ol>
<li>(a) remplir la fiche d'identification sous format électronique sur laquelle il indiquera toutes les coordonnées demandées.</li>
<li>(b) remplir le bon de commande sous format électronique. Dans le formulaire de commande est contenu des informations sur les caractéristiques essentielles de chacun du ou des articles commandés et le prix qui s'y réfère (y compris les taxes et impôts applicables), des moyens de paiement que le client pourra utiliser pour acheter chaque article et les modalités de livraison du ou des articles.</li>
<li>© valider sa commande après l'avoir vérifiée et corrigé d'éventuelles erreurs.</li>
<li>(d) choisir le mode de paiement de sa commande et le cas échéant, effectuer le paiement dans les conditions prévues et décrites à l'article 7.</li>
<li>(e) confirmer sa commande et son règlement.</li>
</ol>
</li>
<li>3.2 Pour conclure un contrat d'achat d'un ou plusieurs articles sur la " Boutique en ligne ", le client doit obligatoirement:</li>
<li>3.4 L'ensemble des données fournies et la confirmation enregistrée vaudront preuve de la transaction. La confirmation vaudra signature et acceptation des opérations effectuées.</li>
</ol>
<h2>4. ENREGISTREMENT DE LA COMMANDE</h2>
<ol>
<li>4.1 La commande ne sera définitivement enregistrée qu'a la validation de l'écran récapitulatif de commande. A compter de cette dernière validation, la commande est considérée comme irrévocable et ne peut être remise en cause que dans les conditions définies ci-après.</li>
<li>4.2 Le formulaire de commande sera archivé dans la banque de données SABRE pendant le temps nécessaire à l'expédition de la commande et dans le respect des délais légaux. Le client pourra accéder à son formulaire de commande, sur simple demande.</li>
</ol>
<h2>5. CONFIRMATION DE LA COMMANDE</h2>
<ol>
<li>5.1 Une fois le contrat conclu selon la procédure de passation de commande décrite, SABRE transmettra au client par courrier électronique un reçu de sa commande récapitulant les informations contenues dans le formulaire de commande enregistré.</li>
<li>5.2 En conservant ce mail et/ou en l'imprimant le client détient une preuve de la commande que SABRE lui recommande de conserver.</li>
<li>5.3 Toutefois, ce mail confirme uniquement que la commande du client a été prise en compte par SABRE et non que le ou les articles commandés soient disponibles.</li>
<li>5.4 Dans le cas ou le ou les articles commandés présentés sur la " Boutique en ligne ", ne seraient plus disponibles ou en vente au moment de l'envoi par le client du formulaire de commande, SABRE informera le client, dans les meilleurs délais et dans tous les cas avant l'écoulement de trente (30) jours ouvrables à partir du jour suivant lequel le client a transmis son formulaire de commande à SABRE, de l'éventuelle indisponibilité du ou des articles commandés.</li>
</ol>
<h2>6. PRIX DE VENTE</h2>
<ol>
<li>6.1 Les prix de vente des articles sont exprimés en euros toutes taxes comprises hors frais de livraison. Les éventuels frais de livraison indiqués à l'article 10 ci-dessous sont à la charge du client, sauf accord écrit de SABRE.</li>
<li>6.2 Les prix des articles peuvent évoluer à tout moment. Le prix de vente retenu pour l'achat de chaque article correspond celui observé sur la " Boutique en ligne " au moment de l'enregistrement de la commande, sauf erreur typographique.</li>
<li>6.3 Tout changement de taux de la taxe sur la valeur ajoutée (T.V.A) sera répercuté immédiatement sur le prix de vente des articles.</li>
<li>6.4 Toute commande passée sur le E-shop SABRE pourra être soumise à des taxes éventuelles et à des droits de douane dans le pays de livraison. Ces frais supplémentaires liés à la livraison d’un de nos produits sont à la charge du client et relèvent de sa responsabilité. Pour plus de précisions, vous pouvez consulter le site des douanes de votre pays.</li>
</ol>
<h2>7. MODES DE PAIEMENT ET FACTURATION</h2>
<ol>
<li>7.1 Tous les achats effectués sur la " Boutique en ligne " sont payables à la commande.</li>
<li>7.2 Pour le paiement du prix de vente du ou des articles commandés, le client pourra suivre un des modes de paiement indiqués dans le formulaire de commande.</li>
<li>7.3 Les modes de paiements acceptés par SABRE pour le règlement du ou des achats effectués sur la " Boutique en ligne " sont la carte de crédit, et le chèque (Sachant que la commande ne sera envoyée qu'après encaissement du dit chèque).</li>
<li>7.4 Paiement carte Le client doit indiquer sur le formulaire de commande le numéro de sa carte de crédit, sa date limite de validité ainsi que les trois derniers numéros situés au dos de la carte de crédit dans les champs prévus à cet effet. La saisie de ces informations est sécurisée par le CIC.</li>
<li>7.5 Paiement Chèque Le chèque doit être établi à l'ordre de SABRE, au montant exact de la commande du client signé et daté par lui seul. Le client doit inscrire au dos de ce chèque le numéro de sa commande, et l'envoyer par la poste affranchie à l'adresse suivante : SABRE " Boutique en ligne " 21 Avenue de l'Europe 78400 CHATOU
<ol>
<li>(a) Ces informations ne seront jamais utilisées par SABRE à d'autres fins que de compléter les procédures liées à l'achat du client, opérer les éventuels remboursements en cas d'éventuelles restitutions des articles ou de signaler aux autorités compétentes la survenance de fraudes sur la "Boutique en ligne".</li>
<li>(b) SABRE se réserve le droit de suspendre toute gestion de commande et toute livraison en cas de refus d'autorisation de paiement.</li>
</ol>
</li>
<li>7.6 SABRE se réserve le droit de refuser d'effectuer une livraison ou d'honorer une commande passée par un client qui n'aurait pas totalement ou partiellement payé une commande précédente ou avec lequel un litige de paiement est en cours.</li>
<li>7.7 SABRE se réserve le droit de poursuivre toutes utilisations ou toutes tentatives d'utilisation de moyens de paiement frauduleux.</li>
</ol>
<h2>8. MODALITES DE LIVRAISON</h2>
<ol>
<li>8.1 La livraison se fait à l'adresse indiquée par le client sur le formulaire de commande qui ne peut être que dans la zone géographique de livraison.</li>
<li>8.2 Zone Géographique de livraison
<ol>
<li>(a) Les articles mis en vente par SABRE sur la " Boutique en ligne " sont livrés en France Métropolitaine.</li>
</ol>
</li>
<li>8,3 La livraison est opérée par Transporteur UPS.</li>
<li>8.4 En cas d'absence du Client lors de la livraison, un avis de passage sera déposé dans sa bo®te aux lettres l'informant du passage du service de livraison et l'invitant prendre contact avec UPS.</li>
<li>8.5 La livraison est considérée comme réalisée à la date de la première présentation du ou des articles commandés dans son/leur colis à l'adresse de livraison indiquée par le client sur le formulaire de commande.</li>
<li>8.6 La remise du ou des articles commandés sera effectuée contre signature par le client d'un bon de livraison.</li>
<li>8.7 Le client est tenu de vérifier l'état de l'emballage et la conformité du ou des articles livrés avant de signer le bon de livraison. Tout vice ou toute non conformité concernant la livraison telle que, par exemple, avarie, article manquant ou endommagé, devra être impérativement indiqué sur le bon de livraison et accompagné de la signature du client.</li>
<li>
8.8 Le client devra confirmer le vice ou la non-conformité détectés en adressant au transporteur avec copie à SABRE dans un délai de quinze (7) jours ouvrables à compter de la date de livraison du ou des articles au client une lettre de réclamation recommandée avec accusé de réception exposant le vice ou la non-conformité constaté.
<ol>
<li>Adresse du Transporteur: UPS 460 Rue du Valibout 78370 PLAISIR.</li>
<li>Adresse de SABRE 21 avenue de l'Europe 78400 CHATOU.</li>
</ol>
</li>
<li>8.9 A défaut d'une réclamation effectuée dans le délai précité, le ou les articles livrés seront réputés conformes et non viciés et acceptés par le client. Aucun article vicié ou non-conforme ne pourra être échangé avant d'avoir été réexpédié et réceptionné par SABRE, en bon état, tel que livré par les soins du transporteur et dans son emballage d'origine.</li>
</ol>
<h2>9. DELAI DE LIVRAISON</h2>
<ol>
<li>9.1 Sauf en cas de Force Majeure telle que définie l'article 14, les délais de livraison seront dans la limite des stocks disponibles, ceux indiqués ci-dessous. Livraison normale 5 à 7 jours ouvrables.</li>
<li>9.2 En cas d'indisponibilité de la part de SABRE de livrer dans les délais indiqués il sera proposé au client le remboursement de son achat ou un article de remplacement d'une valeur équivalente.</li>
<li>9.4 En cas de retard dans la livraison la responsabilité de SABRE ne pourra pas être recherchée et ce, pour quelque cause que ce soit et aucune demande d'indemnisation, de quelque nature que ce soit, ne pourra être réclamée par le client à SABRE.</li>
</ol>
<h2>10. FRAIS DE LIVRAISON</h2>
<ol>
<li>10.1 Les frais de livraison sont à la charge du client sauf accord écrit de SABRE</li>
</ol>
<h2>11. DROIT DE RETRACTATION</h2>
<ol>
<li>11.1 Le client bénéficie d'un délai de rétractation de sept jours à compter de la livraison de la commande effectuée sur la " Boutique en ligne "; ce délai est prorogé si le 7eme jour est un samedi ou un dimanche.</li>
<li>11.2 Durant ce délai, le client peut retourner, gratuitement et sans autres pénalités le ou les articles ne lui convenant pas.</li>
<li>
11.3 Un tel retour ne sera accepté par SABRE que si:
<ol>
<li>a) Le client a, avant tout retour d'un ou de plusieurs articles, contacté impérativement SABRE en appelant le numéro 01 30 09 50 00.</li>
<li>(b) Le ou les articles retournés ne doivent avoir été ni utilisés, ni modifiés, ni lavés, ni abimés.</li>
<li>(c) Le ou les articles retournés se trouvent encore dans leur emballage d'origine.</li>
<li>(d) Les articles retournés sont envoyés en une ou plusieurs expéditions.</li>
</ol>
</li>
<li>11.4 Si ce droit de rétractation est exercé dans les conditions définies ci-dessus, SABRE fera le nécessaire pour rembourser les éventuelles sommes déjà encaissées en contrepartie de l'achat des articles, net des frais de livraison des articles commandés.</li>
<li>11.5 Les sommes éventuellement déjà encaissées seront remboursées dans les meilleurs délais et dans tous les cas, dans les trente (30) jours ouvrables suivant la date laquelle SABRE aura eu connaissance de l'exercice du droit de rétractation par le client et cela quel que soit le mode de paiement utilisé.</li>
<li>11.6 Dans le cas oû les conditions d'exercice du droit de rétractation décrites ci-dessus n'auraient pas été respectées, le client pourra obtenir de nouveau et à ses frais les articles retournés à SABRE dans l'état oû ils ont été restitués à SABRE.</li>
</ol>
<h2>12. SERVICE CLIENT</h2>
<ol>
<li>Pour toute demande d'information ou d'éventuelles réclamations, le client peut contacter SABRE au numéro 01 30 09 50 00 ou par e-mail à contact@sabre.fr</li>
</ol>
<h2>13. RESERVE DE PROPRIETE ET TRANSFERT DES RISQUES</h2>
<ol>
<li>13.1 Sabre se reverve expressement la propriete du ou des articles livrés jusqu'au paiement integral du prix, en principal et en interets.</li>
<li>13.2 La simple remise des titres de paiement ne constitue pas un paiement.</li>
<li>13.3 En cas d'inexécution par le client de ses obligations de paiement, pour quelque cause que ce soit, SABRE sera en droit d'exiger la restitution immédiate aux seuls frais du client, du ou des articles livrés.</li>
<li>13.4 Les dispositions ci-dessus ne font pas obstacles au transfert des risques de perte ou de détérioration sur le client. Les risques de pertes ou de détérioration sont à la charge du client à compter du moment oû les produits ont quitté les locaux de SABRE.</li>
</ol>
<h2>14. FORCE MAJEURE</h2>
<ol>
<li>La responsabilité de SABRE ne pourra pas être engagée pour tout manquement à ses obligations contractuelles dans l'hypothèse de force majeure et fortuite telle que notamment les catastrophes, incendies, grèves internes ou externes, défaillances ou pannes internes ou externes, et d'une manière générale tout évènement extérieur, imprévisible et irrésistible ne permettant pas la bonne exécution des commandes.</li>
</ol>
<h2>15. UTILISATION DU SITE</h2>
<ol>
<li>15.1 Tous les éléments du site SABRE sont et restent la propriété intellectuelle et exclusive de SABRE.</li>
<li>15.2 Personne n'est autorisé à reproduire, exploiter, rediffuser, ou utiliser à quelque titre que ce soit, même partiellement, des éléments du site qu'ils soient logiciels, visuels, ou sonores.</li>
<li>15.3 Tout lien simple ou par hypertexte est strictement interdit sans un accord écrit exprès de SABRE.</li>
<li>15.4 SABRE, dans le processus de vente en ligne, n'est tenu que par une obligation de moyens ; sa responsabilité ne pourra être engagée pour un dommage résultant de l'utilisation du réseau internet tel que perte de données, intrusion, virus, rupture du service, ou autres problèmes involontaires.</li>
</ol>
<h2>16. DONNEES A CARACTERE PERSONNEL, POLITIQUE DE CONFIDENTIALITE</h2>
<ol>
<li>16.1 Conformément à la loi relative à l'informatique, aux fichiers et aux libertés du 6 janvier 1978, les informations à caractère nominatif relatives aux clients pourront faire l'objet d'un traitement automatisé.</li>
<li>16.2 SABRE se réserve le droit de collecter des informations sur les clients y compris en utilisant des cookies, et, s'il le souhaite, de transmettre à des partenaires commerciaux les informations collectées.</li>
<li>16.3 Les clients peuvent s'opposer à la divulgation de leurs coordonnées en le signalant à SABRE.</li>
<li>16.4 Les clients disposent d'un droit d'accès et de rectification des données les concernant, conformément à la loi du 6 janvier 1978.</li>
<li>16.5 L'ensemble de ces indications pourra être consulté par le client sur l'espace Politique de Confidentialité. Pour toute autre information sur le traitement des données personnelles, le client peut adresser ses demandes à SABRE à l'adresse e-mail info@sabre.fr ou par courrier à SABRE " Boutique en ligne " 21 avenue de l'Europe 78400 CHATOU.</li>
</ol>
<h2>17. ARCHIVAGE-PREUVE</h2>
<ol>
<li>17.1 SABRE archivera les bons de commandes et les factures sur un support fiable et durable constituant une copie fidèle conformément aux dispositions de l'article 1348 du Code civil.</li>
<li>17.2 Les registres informatisés de SABRE seront considérés par les parties comme preuve des communications, commandes, paiements et transactions intervenus entre les parties.</li>
</ol>
<h2>18. DROIT APPLICABLE ET REGLEMENT DES LITIGES</h2>
<ol>
<li>18.1 Les présentes conditions de vente en ligne sont soumises à la loi française.</li>
<li>18.2 En cas de litige, compétence est attribuée aux tribunaux français compétents nonobstant pluralité de défendeurs ou appel en garantie.</li>
</ol>
</div>
HTML;

//page confidentialité

$mentionContent6=
    <<<HTML
<div class="mention conditions-page cookies">

<h1>Cookies</h1>
<h2>Qu'est-ce qu'un cookie ?</h2>
<p>
 Ce ne sont pas que des g&acirc;teaux truff&eacute;s de p&eacute;pites de chocolat anglo-saxons.Un cookie, dans le langage informatique,
 est un fichier d&eacute;pos&eacute; sur le disque dur d&rsquo;un ordinateur lors d&rsquo;une visite effectu&eacute;e sur un site Web.
 Le but de ces cookies est de recueillir des informations vous concernant afin de suivre et faciliter votre navigation, et de vous adresser
 des recommandations et des offres personnalis&eacute;es. Notez que ces fichiers ne sont en aucun cas malveillants.
</p>
<h2>A quelles fins utilisons-nous des cookies ?</h2>
<p>
Le but d&rsquo;Sabre est de faire des offres personnalis&eacute;es et individuelles &agrave; chacun de ses clients. A cet effet, nous d&eacute;posons des cookies pour collecter des informations vous concernant (contenu de votre panier d&rsquo;achat,
fiches produits consult&eacute;es). Cela nous permettra d&rsquo;optimiser et de faciliter votre navigation sur le site, y compris lors de vos prochaines visites.
 </p>
<h2>Les diff&eacute;rents types de cookies utilis&eacute;s sur notre site</h2>
<p>
Dans le but de vous proposer des services personnalis&eacute;s, nous utilisons diff&eacute;rents types de cookies.
<h3>1. Des cookies indispensables &agrave; l&rsquo;utilisation du site</h3>
<ol>
<li>
1.1 Il s&rsquo;agit notamment des cookies panier d&rsquo;achat, d&rsquo;authentification de session, nous permettant de faciliter votre visite. Sans ces donn&eacute;es, toutes les
fonctionnalit&eacute;s du site ne pourront &ecirc;tre assur&eacute;es.
</li>
<li>1.2 Ces cookies sont d&eacute;pos&eacute;s uniquement par Sabre.</li>
</ol>
<h3>2. Des cookies n&eacute;cessaires pour optimiser votre utilisation du site</h3>
<ol>
<li>
2.1 Il s&rsquo;agit de fichiers de suivi.Ces cookies nous permettent d&rsquo;optimiser au mieux notre site et &eacute;galement de prendre en compte vos pr&eacute;f&eacute;rences
avant de vous proposer des contenus plus cibl&eacute;s &agrave; vos int&eacute;r&ecirc;ts.
</li>
<li>
2.2 Ces cookies sont d&eacute;pos&eacute;s par Sabre ou ses prestataires. Toutes les donn&eacute;es fournies sont anonymes, aucune information personnelle n&rsquo;est transmise</p>
</li>
</ol>
</p>
<h2>Comment g&eacute;rer les cookies ?</h2>
<p >
Le param&eacute;trage de vos cookies est susceptible d&rsquo;entrainer une modification de vos conditions d&rsquo;acc&egrave;s &agrave; notre site et votre navigation sur Internet en g&eacute;n&eacute;ral. Vous avez deux mani&egrave;res de g&eacute;rer vos cookies.
</p>
<h3>
1. En exprimant votre choix ici
</h3>
<form action="" name="formCNIL">
<input onclick="aYaline.cnil.enableTracking();" type="radio" value="ok" name="acceptCookieCNIL" id="use_cookies_yes" />
<label  for="use_cookies_yes">Oui</label>
 <input onclick="aYaline.cnil.disableTracking();" type="radio" value="ko" name="acceptCookieCNIL"  id="use_cookies_no" />
 <label for="use_cookies_no">Non</label>
 </form>
<p>
Ces cookies sont d&eacute;pos&eacute;s uniquement par Sabre.
</p>
<h3>
2. En g&eacute;rant manuellement vos cookies depuis votrenavigateur Internet
</h3>
<ol>
<li>
 a. Google Chrome (Windows, Mac OS, Chrome OS)
 <ul>
 <li>1- Cliquez sur le menu Chromedans la barre d'outils du navigateur.</li>
 <li>2- S&eacute;lectionnez &laquo; Param&egrave;tres &raquo;.</li>
 <li>3- En bas, cliquez sur &laquo; Afficher les param&egrave;tres avanc&eacute;s &raquo;.</li>
 <li>4- Dans la section &laquo; Confidentialit&eacute; &raquo;, cliquez sur le bouton &laquo; Param&egrave;tres de contenu &raquo;.</li>
 <li>5- Dans la section &laquo; Cookies &raquo;, s&eacute;lectionnez le param&eacute;trage que vous souhaitez.</li>
</ul>
</li>
</ol>
<ol>
<li>
 b. Internet Explorer (Windows)
 <ul>
 <li>1- Dans Internet Explorer, cliquez sur le bouton &laquo; Outils &raquo;, puis sur &laquo; Options Internet &raquo;.</li>
 <li>2- Sous l'onglet &laquo; Confidentialit&eacute; &raquo;, regardez la section &laquo; Param&egrave;tres &raquo;.</li>
 <li>3- S&eacute;lectionnez le param&eacute;trage que vous souhaitez.</li>
 <li>4- En bas de lafen&ecirc;tre, cliquez sur &laquo; OK &raquo; pour confirmer votre choix.</li>
</ul>
</li>
</ol>
<ol>
<li>
 c. Mozilla Firefox (Windows, Mac OS, Linux)
 <ul>
 <li>1- Allez dans l'onglet &laquo; Outils &raquo; du navigateur puis s&eacute;lectionnez le menu &laquo; Options &raquo;.</li>
 <li>2- Dans la fen&ecirc;tre qui s'affiche, choisissez &laquo; Vie priv&eacute;e &raquo;.</li>
 <li>3- S&eacute;lectionnez votre param&eacute;trage et cliquez sur &laquo; OK &raquo; pour confirmer votre choix.</li>
</ul>
</li>
</ol>
<ol>
<li>
 d. Apple Safari (Windows, Mac OS)
 <ul>
 <li>1- Dans votre navigateur, choisissez le menu &Eacute;dition &gt;Pr&eacute;f&eacute;rences.</li>
 <li>2- Cliquez sur &laquo; S&eacute;curit&eacute; &raquo;.</li>
 <li>3- Dans la partie &laquo; Accepter les cookies &raquo;, choisissez l&rsquo;option qui vous int&eacute;resse.</li>
</ul>
</li>
</ol>
<ol>
<li>
 e. Op&eacute;ra (Windows, Mac OS, Linux)
 <ul>
 <li>1- Dans les r&eacute;glages votre navigateur, allez sur &laquo; Vie priv&eacute;e &amp; S&eacute;curit&eacute; &raquo;.</li>
 <li>2- Dans la partie &laquo; Cookies &raquo;, choisissez l&rsquo;option qui vous int&eacute;resse.</li>
 <li>3- Fermez la page.</li>
</ul>
</li>
</ol>
</div>
HTML;

//page catalogue pdf

$mentionContent7=
    <<<HTML

HTML;


//BLOCKS
// block Description
$description=
    <<<HTML
<div>
<p><span>"sabre" vient du nom &eacute;ponyme du premier couvert d&eacute;velopp&eacute; par la marque.</span></p>
<p><span> &eacute;voquant la tradition, le savoir faire, il symbolise bien la lame qui coupe les liens avec le conformisme. </span></p>
<p>voil&agrave; un nom qui en dit long sur &agrave; cette marque ou se m&ecirc;le chic et s&eacute;cal&eacute;. les arts de la table ont bien chang&eacute;, aujourd'hui on ose les m&eacute;langes, on se lasse des services uniques, on aime marier et mixer. sabre l'a bien compris et ce depuis maintenant 17 ans! en mettant &agrave; disposition de leur client&egrave;le un grand nombres de motifs, et de couleurs, sabre r&eacute;pond &agrave; un vrai besoin de libert&eacute; gr&acirc;ce &agrave; des combinaisons presque infinies. tout cela participe &agrave; cr&eacute;er ces collections gourmandes, aux couleurs vivantes et gaies. un univers qui met tout le monde d'accord, des plus fantaisistes aux plus sages, toutes s'y retrouvent.</p>
</div>
<div>
<p><span> la flexibilit&eacute; est une notion importante chez sabre, </span></p>
<p><span>on la cultive pour la plus grande satisfaction de la client&egrave;le. </span></p>
<p>leur valeur ajout&eacute;e c'est de pouvoir produire &agrave; la commande, et ainsi de permettre aux clients(es) de composer leur service sur mesure.</p>
</div>
HTML;

//block Boutique
$boutique=
    <<<HTML
<a href="{{store url='shop/index/index'}}">
<h2><span>Nos boutiques</span></h2>
<img alt="" src="{{skin url='images/boutiques.jpg'}}" />
</a>
HTML;
//block Esprit

$esprit=
    <<<HTML
<a href="{{store url='marque'}}">
<h2><span>L'esprit Sabre</span></h2>
<img alt="" src="{{skin url='images/esprit.jpg'}}" />
</a>
HTML;

//block Réassurance
$reassurance=
    <<<HTML
<div class="reasurance">
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
HTML;

//block Footer Links
$footer_links=
    <<<HTML
<div class="bloc">
<ul>
<li><a>Les couverts</a></li>
<li><a>La porcelaine</a></li>
<li><a>Les accessoires</a></li>
</ul>
<ul>
<li><a href="{{store url='marque'}}">L'esprit Sabre</a></li>
<li><a  href="{{store url='shop/index/index'}}">Nos boutiques</a></li>
</ul>
</div>
<div class="bloc">
<ul>
<li><a href="{{store url='livraison'}}">Livraison</a></li>
<li><a href="{{store url='paiement-securise'}}">Paiement</a></li>
<li><a href="{{store url='service-client'}}">Service client</a></li>
</ul>
<ul>
<li><a href="{{store url='conditions-generales-vente'}}" >CGV</a></li>
<li><a href="{{store url='confidentialite'}}">Confidentialité</a></li>
</ul>
</div>
<div class="bloc">
<ul>
<li><a href="{{store url='catalogue-pdf'}}">Catalogue PDF</a></li>
<li><a href="{{store url='catalog/seo_sitemap/category/'}}" >Plan du site</a></li>
</ul>
</div>
HTML;

//block Footer Links Company
$footer_links_company=
    <<<HTML
<div class="links">
<ul>
<li>&copy; 2015 SABRE Paris</li>
<li><a href="{{store url='mentions-legales'}}">Mentions légales</a></li>
<li><a href="http://www.ayaline.com" target="_blank">R&eacute;alisation&nbsp; <img alt="" src="{{skin url='images/pictos/ayaline.svg'}}" /> </a></li>
</ul>
</div>
HTML;

//Réseaux sociaux
$rs=
    <<<HTML
<div class="rs">
<p>Suivez-nous</p>
<ul>
<li><a href='https://www.facebook.com/pages/SABRE-PARIS/131241179309'> <img alt="FaceBook" src="{{skin url='images/pictos/rs_facebook.svg'}}" /></a></li>
<li><a href='https://twitter.com/SabreParis'> <img alt="Twitter" src="{{skin url='images/pictos/rs_twitter.svg'}}" /></a></li>
<li><a href="http://www.pinterest.com/sabreparis/"> <img alt="Pinterest" src="{{skin url='images/pictos/rs_pin.svg'}}" /></a></li>

</ul>
</div>
HTML;



$contents = [
    'pages' => [

        [
            'identifier' => 'mentions-legales',
            'content' => $mentionContent,
            'title' => 'Mentions legales'
        ],
        [
            'identifier' => 'marque',
            'content' => $mentionContent1,
            'title' => 'Marque'
        ],
        [
            'identifier' => 'livraison',
            'content' => $mentionContent2,
            'title' => 'Livraison'
        ],
        [
            'identifier' => 'paiement-securise',
            'content' => $mentionContent3,
            'title' => 'Paiement Securise '
        ],
        [
            'identifier' => 'service-client',
            'content' => $mentionContent4,
            'title' => 'Service Client'
        ],
        [
            'identifier' => 'conditions-generales-vente',
            'content' => $mentionContent5,
            'title' => 'Conditions Generales Vente'
        ],
        [
            'identifier' => 'confidentialite',
            'content' => $mentionContent6,
            'title' => 'Confidentialite'
        ],
        [
            'identifier' => 'catalogue-pdf',
            'content' => $mentionContent7,
            'title' => 'Catalogue PDF'
        ]
    ],

    'blocks' => [
        [
            'identifier' => 'descriptions',
            'content' => $description,
            'title' => 'Description'
        ],
        [
            'identifier' => 'boutiques',
            'content' => $boutique,
            'title' => 'Boutique Store'
        ],
        [
            'identifier' => 'esprit-sabre',
            'content' => $esprit,
            'title' => 'L’esprit Sabre Store'
        ],
        [
            'identifier' => 'reassurance',
            'content' => $reassurance,
            'title' => 'Réassurance Store'
        ],
        [
            'identifier' => 'footer_links',
            'content' => $footer_links,
            'title' => 'Footer links Store'
        ],
        [
            'identifier' => 'footer_links_company',
            'content' => $footer_links_company,
            'title' => 'Footer Links Company Store'
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

        if ($_type === 'pages') {
            $_page = Mage::getModel('cms/page')->setStoreId($frenchStoreId)->load($_content['identifier'], 'identifier');
            $_page->setData('identifier', $_content['identifier']);
            $_page->setData('content', $_content['content']);
            $_page->setData('title', $_content['title']);
            $_page->setData('stores', [$frenchStoreId]);
            $_page->setIsActive(1);
            $_page->setRootTemplate("one_column");
            $_page->save();

            $_page = Mage::getModel('cms/page')->setStoreId($englishStoreId)->load($_content['identifier'], 'identifier');
            $_page->setData('identifier', $_content['identifier']);
            $_page->setData('content', $_content['content']);
            $_page->setData('title', $_content['title']);
            $_page->setData('stores', [$englishStoreId]);
            $_page->setIsActive(1);
            $_page->setRootTemplate("one_column");
            $_page->save();


        } else {
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
}

// Les models

$models = Mage::helper('sabre_configuration')->getAllProductAttributeAdminLabel('a_model');

foreach ($models as $_model){
    $model['identifier'] = 'model-'.$_model['label'];
    $model['title'] = $_model['label'];
    $model['stores_french'] = $frenchStoreId;
    $model['stores_english'] = $englishStoreId;
    $model['is_active'] = 1;
    $model['content']=
        <<<HTML
           <div class="bloc center">
              <img alt="" src="{{skin url='images/aquarelle.jpg'}}" />
              <span>$_model[label]</span>
           </div>
           <div class="bloc right">
              <img alt="" src="{{skin url='images/aquarelle2.jpg'}}" />
           </div>
           <div class="bloc left">
              <img alt="" src="{{skin url='images/aquarelle3.jpg'}}" />
           </div>
HTML;

    Mage::getModel('cms/block')->setStoreId($frenchStoreId)->load($model['identifier'],'identifier')
        ->setContent($model['content'])
        ->setIdentifier($model['identifier'])
        ->setTitle($model['title'])
        ->setStores($model['stores_french'] )
        ->setActive($model['is_active'] )
        ->save();

    Mage::getModel('cms/block')->setStoreId($englishStoreId)->load($model['identifier'],'identifier')
        ->setContent($model['content'])
        ->setIdentifier($model['identifier'])
        ->setTitle($model['title'])
        ->setStores($model['stores_english'] )
        ->setActive($model['is_active'] )
        ->save();
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
<div>{{widget type="catalog/product_widget_new" display_type="all_products" products_count="4" template="catalog/product/widget/new/content/new_grid.phtml"}}</div>
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
<div>{{widget type="catalog/product_widget_new" display_type="all_products" products_count="4" template="catalog/product/widget/new/content/new_grid.phtml"}}</div>
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





