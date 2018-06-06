<?php
/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    nabil agarodouh
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/** @var $this Mage_Core_Model_Resource_Setup */

//store french

$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();


$customer = '$customer';
$order = '$order';
$shipment = '$shipment';
$billing = '$billing';
$back_url = '$back_url';
$user = '$user';
$name = '$name';
$userName = '$userName';
$applicationName = '$applicationName';
$status = '$status';
$creditmemo = '$creditmemo';
$invoice = '$invoice';


/*
$_template = Mage::getModel('core/email_template');
$_template->addData($_data);
$_template->save();*/

//
$enTete =
    <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, width=device-width" />
    <style type="text/css">
    <!--
   .action-button td{padding:0 5px!important;}
   -->
    </style>
</head>
<body>
{{var non_inline_styles}}
<!-- Begin wrapper table -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="background-table">
    <tr>
        <td valign="top" class="container-td" align="center">
            <table cellpadding="0" cellspacing="0" border="0" align="center" class="container-table">
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" border="0" class="logo-container">
                            <tr>
                                <td class="logo">
                                    <a href="{{store url=''}}">
                                        <img
                                            {{if logo_width}}
                                            width="{{var logo_width}}"
                                            {{else}}
                                            width="165"
                                            {{/if}}

                                            {{if logo_height}}
                                            height="{{var logo_height}}"
                                            {{else}}
                                            height="48"
                                            {{/if}}

                                            src="{{var logo_url}}"
                                            alt="{{var logo_alt}}"
                                            border="0"/>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="top-content">
                    <!-- Begin Content -->
HTML;
//
$piedPage =
    <<<HTML
<!-- End Content -->
                    </td>
                </tr>
            </table>
            <h5 class="closing-text">Merci, {{var store.getFrontendName()}}!</h5>
        </td>
    </tr>
</table>
<!-- End wrapper table -->
</body>
HTML;
//
$nvCompte =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h1>Bienvenue à {{var store.getFrontendName()}}.</h1>
            <p>Pour vous connecter lors de vos visites sur  notre site il vous suffit de cliquer sur <a href="{{store url='customer/account/'}}">Connexion</a> ou <a href="{{store url='customer/account/'}}">Mon compte</a> en haut de chaque page, puis de saisir votre adresse e-mail et votre mot de passe.</p>
            <p class="highlighted-text">
              Utilisez les données suivantes lorsque vous êtes invité à vous connecter :<br/>
                <strong>Email</strong> : {{var customer.email}}<br/>
                <strong>Mot de passe</strong> : <small>vous seul le connaissez</small>
            </p>
            <p>En vous connectant à votre compte, vous pourrez :</p>
            <ul>
                <li>Passer vos commandes plus rapidement</li>
                <li>Suivre l'évolution de vos commandes</li>
                <li>Consulter l'historique de vos commandes</li>
                <li>Apporter des modifications aux informations de votre compte</li>
                <li>Modifier votre mot de passe</li>
                <li>Enregistrer des adresses alternatives (pour l'expédition aux plusieurs membres de la famille et amis!)</li>
            </ul>
            <p>
                Si vous avez des questions,  vous pouvez nous contacter au
                <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                {{depend store_phone}}ou par téléphone au <a href="tel:{{var phone}}">{{var store_phone}}</a>{{/depend}}.
            </p>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$nvCompteConfirme =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h1>{{htmlescape var=$customer.name}} ,</h1>
            <p>Pour vous connecter lors de vos visites sur  notre site il vous suffit de cliquer sur <a href="{{store url='customer/account/'}}">Connexion</a> ou <a href="{{store url='customer/account/'}}">Mon compte</a> en haut de chaque page, puis de saisir votre adresse e-mail et votre mot de passe.</p>
            <p>En vous connectant à votre compte, vous pourrez :</p>
            <ul>
                <li>Passer vos commandes plus rapidement</li>
                <li>Suivre l'évolution de vos commandes</li>
                <li>Consulter l'historique de vos commandes</li>
                <li>Apporter des modifications aux informations de votre compte</li>
                <li>Modifier votre mot de passe</li>
                <li>Enregistrer des adresses alternatives (pour l'expédition aux plusieurs membres de la famille et amis!)</li>
            </ul>
            <p>
                Si vous avez des questions,  vous pouvez nous contacter au
                <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                {{depend store_phone}} ou par téléphone au <a href="tel:{{var phone}}">{{var store_phone}}</a>{{/depend}}.
            </p>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$nvCleConfirme =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="action-content">
                        <h1>{{htmlescape var=$customer.name}},</h1>
                        <p>Votre e-mail {{var customer.email}} doit être confirmée avant de l'utiliser pour vous connecter à notre boutique.</p>
                        <p class="highlighted-text">
                            Utilisez les données suivantes lorsque vous êtes invité à vous connecter :<br/>
                            <strong>E-mail : </strong> {{var customer.email}}<br/>
                            <strong>Mot de passe : </strong> <small>vous seul le connaissez</small>
                        </p>
                        <p>Cliquez ici pour confirmer votre e-mail et s'identifier (le lien est valable une seule fois) :</p>
                        <table cellspacing="0" cellpadding="0" class="action-button" >
                            <tr>
                                <td>
                                    <a href="{{store url='customer/account/confirm/' _query_id=$customer.id _query_key=$customer.confirmation _query_back_url=$back_url}}"><span>Confirmez compte</span></a>
                                </td>
                            </tr>
                        </table>
                        <p>
                            Si vous avez des questions,  vous pouvez nous contacter au
                            <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{depend store_phone}} ou par téléphone au <a href="tel:{{var phone}}">{{var store_phone}}</a>{{/depend}}.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$souscriptionNewsletter =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h4>Vous avez été inscrit avec succès à la newsletter.</h4>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$alertDispoProduct =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h1>{{var customerName}},</h1>

            {{var alertGrid}}
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$alertPrixProduct =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h1>{{var customerName}},</h1>

            {{var alertGrid}}
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$alertUpDateDevises =
    <<<HTML
Alertes de mise à jour des devises:
{{var warnings}}
HTML;
//
$alertNettoyageLogs =
    <<<HTML
Alertes de nettoyage de logs :
{{var warnings}}
HTML;
//
$changStatutJeton =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h1>Bonjour, {{htmlescape var=$userName}}</h1>
            <p>Votre autorisation de <b>{{htmlescape var=$applicationName}}</b> a été changé en <b>{{htmlescape var=$status}}</b> par l'équipe d'administration.</p>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$desabonnementNewsletter =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h4>Vous êtes désabonné de la newsletter.</h4>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$envoiProdAmi =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h1>{{htmlescape var=$name}},</h1>
            <p>Votre ami veut partager avec vous ce produit : <a href="{{var product_url}}">{{var product_name}}</a></p>
            <table cellspacing="0" cellpadding="0" class="message-container">
                <tr>
                    <td><strong>Message : </strong> {{var message}}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$erreurCron =
    <<<HTML
Erreur de cron pour les alertes produits:
{{var warnings}}
HTML;
//
$erreurPaiement =
    <<<HTML
<table>
    <thead>
    <tr>
        <th>Transaction de paiement a échoué.</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <p>
                <b>Raison</b><br />
                {{var reason}}
            </p>
            <p>
                <b>Type de commande</b><br />
                {{var checkoutType}}
            </p>
            <p>
                <b>Client : </b><br />
                <a href="mailto:{{var customerEmail}}">{{var customer}}</a> &lt;{{var customerEmail}}&gt;
            </p>
            <p>
                <b>Articles</b><br />
                {{var items}}
            </p>
            <p>
                <b>Total : </b><br />
                {{var total}}
            </p>
            <p>
                <b>Adresse de facturation : </b><br />
                {{var billingAddress.format('html')}}
            </p>
            <p>
                <b>Adresse de livraison : </b><br />
                {{var shippingAddress.format('html')}}
            </p>
            <p>
                <b>Méthode de livraison : </b><br />
                {{var shippingMethod}}
            </p>
            <p>
                <b>Méthode de paiement : </b><br />
                {{var paymentMethod}}
            </p>
            <p>
                <b>Date & heure : </b><br />
                {{var dateAndTime}}
            </p>
        </td>
    </tr>
    </tbody>
</table>
HTML;
//
$formContact =
    <<<HTML
Nom : {{var data.name}}
Email : {{var data.email}}
Téléphone : {{var data.telephone}}

Commentaire : {{var data.comment}}
HTML;
//
$siteMapAlerte =
    <<<HTML
Le plan du site a généré des alertes :
{{var warnings}}
HTML;
//
$updateCommande =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="action-content">
                        <h1>{{htmlescape var=$order.getCustomerName()}},</h1>
                        <p>Votre commande <span class="no-link">#{{var order.increment_id}}</span> a été mis à jour à : <strong>{{var order.getStatusLabel()}}</strong></p>
                        {{if comment}}
                        <table cellspacing="0" cellpadding="0" class="message-container">
                            <tr>
                                <td>{{var comment}}</td>
                            </tr>
                        </table>
                        {{/if}}
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                        <p>
                          Si vous avez des questions, vous pouvez nous contacter au
                            <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{depend store_phone}} ou par téléphone au <a href="tel:{{var phone}}">{{var store_phone}}</a>{{/depend}}.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$updatCommandeGuest =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="action-content">
                        <h1>{{htmlescape var=$billing.getName()}},</h1>
                        <p>Votre commande <span class="no-link">#{{var order.increment_id}}</span> a été mis à jour à : <strong>{{var order.getStatusLabel()}}</strong></p>
                        {{if comment}}
                        <table cellspacing="0" cellpadding="0" class="message-container">
                            <tr>
                                <td>{{var comment}}</td>
                            </tr>
                        </table>
                        {{/if}}
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                        <p>
                            Si vous avez des questions, vous pouvez nous contacter au
                            <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{depend store_phone}} ou par téléphone au <a href="tel:{{var phone}}">{{var store_phone}}</a>{{/depend}}.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$updatFacture =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="action-content">
                        <h1>{{htmlescape var=$order.getCustomerName()}},</h1>
                        <p>Votre commande <span class="no-link">#{{var order.increment_id}}</span>a été mis à jour à : <strong>{{var order.getStatusLabel()}}</strong></p>
                        {{if comment}}
                        <table cellspacing="0" cellpadding="0" class="message-container">
                            <tr>
                                <td>{{var comment}}</td>
                            </tr>
                        </table>
                        {{/if}}
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                        <p>
                            Si vous avez des questions, vous pouvez nous contacter au
                            <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{depend store_phone}} ou par téléphone au <a href="tel:{{var phone}}">{{var store_phone}}</a>{{/depend}}.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$updatFactureGuest =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="action-content">
                        <h1>{{htmlescape var=$billing.getName()}},</h1>
                        <p>votre commande  <span class="no-link">#{{var order.increment_id}}</span>a été mis à jour à:  <strong>{{var order.getStatusLabel()}}</strong></p>
                        {{if comment}}
                        <table cellspacing="0" cellpadding="0" class="message-container">
                            <tr>
                                <td>{{var comment}}</td>
                            </tr>
                        </table>
                        {{/if}}
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                        <p>
                              Si vous avez des questions, vous pouvez nous contacter au
                            <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{depend store_phone}}ou par téléphone au <a href="tel:{{var phone}}">{{var store_phone}}</a>{{/depend}}.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$updatAvoir =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="action-content">
                        <h1>{{htmlescape var=$order.getCustomerName()}},</h1>
                        <p>Votre commande <span class="no-link">#{{var order.increment_id}}</span> a été mis à jour à : <strong>{{var order.getStatusLabel()}}</strong></p>
                        {{if comment}}
                        <table cellspacing="0" cellpadding="0" class="message-container">
                            <tr>
                                <td>{{var comment}}</td>
                            </tr>
                        </table>
                        {{/if}}
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                        <p>
                           Si vous avez des questions, vous pouvez nous contacter au
                            <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{depend store_phone}} ou par téléphone au <a href="tel:{{var phone}}">{{var store_phone}}</a>{{/depend}}.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$updatAvoirGuest =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="action-content">
                        <h1>{{htmlescape var=$billing.getName()}},</h1>
                       <p>Votre commande <span class="no-link">#{{var order.increment_id}}</span> a été mis à jour à : <strong>{{var order.getStatusLabel()}}</strong></p>
                        {{if comment}}
                        <table cellspacing="0" cellpadding="0" class="message-container">
                            <tr>
                                <td>{{var comment}}</td>
                            </tr>
                        </table>
                        {{/if}}
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                        <p>
                          Si vous avez des questions, vous pouvez nous contacter au
                            <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{depend store_phone}} ou par téléphone au <a href="tel:{{var phone}}">{{var store_phone}}</a>{{/depend}}.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$updatExpedition =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}


<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="action-content">
                        <h1>{{htmlescape var=$order.getCustomerName()}},</h1>
                          <p>Votre commande <span class="no-link">#{{var order.increment_id}}</span> a été mis à jour à : <strong>{{var order.getStatusLabel()}}</strong></p>
                        {{if comment}}
                        <table cellspacing="0" cellpadding="0" class="message-container">
                            <tr>
                                <td>{{var comment}}</td>
                            </tr>
                        </table>
                        {{/if}}
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                        <p>
                          Si vous avez des questions, vous pouvez nous contacter au
                            <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{depend store_phone}} ou par téléphone au <a href="tel:{{var phone}}">{{var store_phone}}</a>{{/depend}}.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$updatExpeditionGuest =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="action-content">
                        <h1>{{htmlescape var=$billing.getName()}},</h1>
                           <p>Votre commande <span class="no-link">#{{var order.increment_id}}</span> a été mis à jour à : <strong>{{var order.getStatusLabel()}}</strong></p>
                        {{if comment}}
                        <table cellspacing="0" cellpadding="0" class="message-container">
                            <tr>
                                <td>{{var comment}}</td>
                            </tr>
                        </table>
                        {{/if}}
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                        <p>
                           Si vous avez des questions, vous pouvez nous contacter au
                            <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{depend store_phone}} ou par téléphone au <a href="tel:{{var phone}}">{{var store_phone}}</a>{{/depend}}.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$passwordAdminForgot =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h1>{{htmlescape var=$user.name}},</h1>
            <p>Il y a eu récemment une demande pour changer le mot de passe pour votre compte.</p>
            <p>Vous pouvez modifier votre mot de passe à tout moment en vous connectant à <a href="{{store url='adminhtml/system_account/'}}">votre compte</a>.</p>
            <p>Si vous avez demandé ce changement de mot de passe, cliquez ici pour réinitialiser votre mot de passe :</p>
            <table cellspacing="0" cellpadding="0" class="action-button">
                <tr>
                    <td style="padding:0 5px;">
                        <a href="{{store url='adminhtml/index/resetpassword/' _query_id=$user.id _query_token=$user.rp_token}}"><span>Réinitialiser le mot de passe</span></a>
                    </td>
                </tr>
            </table>
            <p>Si vous n'avez pas effectué cette demande,  vous pouvez ignorer ce message et votre mot de passe restera le même.</p>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$passwordForgot =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h1>{{htmlescape var=$customer.name}},</h1>
             <p>Il y a eu récemment une demande pour changer le mot de passe pour votre compte.</p>
             <p>Si vous avez demandé ce changement de mot de passe, cliquez ici pour réinitialiser votre mot de passe :</p>
            <table cellspacing="0" cellpadding="0" class="action-button" >
                <tr>
                    <td style="padding:0 5px;">
                        <a href="{{store url='customer/account/resetpassword/' _query_id=$customer.id _query_token=$customer.rp_token}}"><span>Réinitialiser le mot de passe</span></a>
                    </td>
                </tr>
            </table>
            <p>Si vous n'avez pas effectué cette demande,  vous pouvez ignorer ce message et votre mot de passe restera le même.</p>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$newAvoir =
    <<<HTMl
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="email-heading">
                        <h1>Merci pour votre commande de {{var store.getFrontendName()}}.</h1>
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                    </td>
                    <td class="store-info">
                        <h4> Questions de commande?</h4>
                        <p>
                            {{depend store_phone}}
                            <b>Appelez nous :</b>
                            <a href="tel:{{var phone}}">{{var store_phone}}</a><br>
                            {{/depend}}
                            {{depend store_hours}}
                            <span class="no-link">{{var store_hours}}</span><br>
                            {{/depend}}
                            {{depend store_email}}
                            <b>Email : </b> <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{/depend}}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="order-details">
            <h3>Votre Avoir <span class="no-link">#{{var creditmemo.increment_id}}</span></h3>
            <p>Commande <span class="no-link">#{{var order.increment_id}}</span></p>
        </td>
    </tr>
    <tr class="order-information">
        <td>
            {{if comment}}
            <table cellspacing="0" cellpadding="0" class="message-container">
                <tr>
                    <td>{{var comment}}</td>
                </tr>
            </table>
            {{/if}}
            {{layout handle="sales_email_order_creditmemo_items" creditmemo=$creditmemo order=$order}}
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="address-details">
                        <h6>Facturer à :</h6>
                        <p><span class="no-link">{{var order.billing_address.format('html')}}</span></p>
                    </td>
                    {{depend order.getIsNotVirtual()}}
                    <td class="address-details">
                        <h6>Envoyer à :</h6>
                        <p><span class="no-link">{{var order.shipping_address.format('html')}}</span></p>
                    </td>
                    {{/depend}}
                </tr>
                <tr>
                    {{depend order.getIsNotVirtual()}}
                    <td class="method-info">
                        <h6>Méthode d'envoi :</h6>
                        <p>{{var order.shipping_description}}</p>
                    </td>
                    {{/depend}}
                    <td class="method-info">
                        <h6>Méthode de paiement :</h6>
                        {{var payment_html}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTMl;
//
$newAvoirGuest =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="email-heading">
                       <h1>Merci pour votre commande de {{var store.getFrontendName()}}.</h1>
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                    </td>
                    <td class="store-info">
                        <h4> Questions de commande?</h4>
                        <p>
                            {{depend store_phone}}
                           <b>Appelez nous :</b>
                            <a href="tel:{{var phone}}">{{var store_phone}}</a><br>
                            {{/depend}}
                            {{depend store_hours}}
                            <span class="no-link">{{var store_hours}}</span><br>
                            {{/depend}}
                            {{depend store_email}}
                            <b>Email : </b> <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{/depend}}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="order-details">
            <h3>Votre Avoir <span class="no-link">#{{var creditmemo.increment_id}}</span></h3>
            <p>Commande <span class="no-link">#{{var order.increment_id}}</span></p>
        </td>
    </tr>
    <tr class="order-information">
        <td>
            {{if comment}}
            <table cellspacing="0" cellpadding="0" class="message-container">
                <tr>
                    <td>{{var comment}}</td>
                </tr>
            </table>
            {{/if}}
            {{layout handle="sales_email_order_creditmemo_items" creditmemo=$creditmemo order=$order}}
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="address-details">
                        <h6>Facturer à :</h6>
                        <p><span class="no-link">{{var order.billing_address.format('html')}}</span></p>
                    </td>
                    {{depend order.getIsNotVirtual()}}
                    <td class="address-details">
                        <h6>Envoyer à :</h6>
                        <p><span class="no-link">{{var order.shipping_address.format('html')}}</span></p>
                    </td>
                    {{/depend}}
                </tr>
                <tr>
                    {{depend order.getIsNotVirtual()}}
                    <td class="method-info">
                        <h6>Méthode d'envoi :</h6>
                        <p>{{var order.shipping_description}}</p>
                    </td>
                    {{/depend}}
                    <td class="method-info">
                        <h6>Méthode de paiement :</h6>
                        {{var payment_html}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$newCommande =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td colspan="4">
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="email-heading" style="background:#fff;">
                        <h1>Merci pour votre commande sur la  {{var store.getFrontendName()}}.</h1>
                        <p>Une fois votre colis  expédié nous vous enverrons un e-mail avec un lien pour suivre votre commande. Le résumé de votre commande est ci-dessous. Merci encore pour votre commande.</p>
                    </td>
                    <td class="store-info" style="background:#fff;width:44%;">
                        <h4>Questions sur votre commande?</h4>
                        <p>
                            {{depend store_phone}}
                            <b>Appelez nous : </b>
                            <a href="tel:{{var phone}}">{{var store_phone}}</a><br>
                            {{/depend}}
                            {{depend store_hours}}
                            <span class="no-link">{{var store_hours}}</span><br>
                            {{/depend}}
                            {{depend store_email}}
                            <b>Email : </b> <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{/depend}}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="order-details" colspan="4">
            <h3>Votre commande N°<span class="no-link">{{var order.increment_id}}</span></h3>
            <p>{{var order.getCreatedAtFormated('short')}}</p>
        </td>
    </tr>
    <tr class="order-information">
        <td>
            {{if order.getEmailCustomerNote()}}
            <table cellspacing="0" cellpadding="0" class="message-container">
                <tr>
                    <td>{{var order.getEmailCustomerNote()}}</td>
                </tr>
            </table>
            {{/if}}
            {{layout handle="sales_email_order_items" order=$order}}
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="address-details" style="width:50%;">
                        <h6>Facturer à :</h6>
                        <p><span class="no-link">{{var order.getBillingAddress().format('html')}}</span></p>
                    </td>
                    {{depend order.getIsNotVirtual()}}
                    <td class="address-details" style="width:50%;">
                        <h6>Envoyer à :</h6>
                        <p><span class="no-link">{{var order.getShippingAddress().format('html')}}</span></p>
                    </td>
                    {{/depend}}
                </tr>
                <tr>
                    {{depend order.getIsNotVirtual()}}
                    <td class="method-info" style="width:50%;">
                        <h6>Méthode d'envoi :</h6>
                        <p>{{var order.shipping_description}}</p>
                    </td>
                    {{/depend}}
                    <td class="method-info" style="width:50%;">
                         <h6>Méthode de paiement :</h6>
                        {{var payment_html}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$newCommandeGuest =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="email-heading">
                        <h1>Merci pour votre commande de  {{var store.getFrontendName()}}.</h1>
                        <p>une fois votre paquet  expédié nous vous enverrons un e-mail avec un lien pour suivre votre commande. le résumé de votre commande est ci-dessous. Merci encore pour votre business.</p>
                    </td>
                    <td class="store-info">
                        <h4>Questions de commande?</h4>
                        <p>
                            {{depend store_phone}}
                            <b>Appelez nous : </b>
                            <a href="tel:{{var phone}}">{{var store_phone}}</a><br>
                            {{/depend}}
                            {{depend store_hours}}
                            <span class="no-link">{{var store_hours}}</span><br>
                            {{/depend}}
                            {{depend store_email}}
                            <b>Email : </b> <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{/depend}}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="order-details">
            <h3>Votre commande <span class="no-link">#{{var order.increment_id}}</span></h3>
            <p>Placé sur {{var order.getCreatedAtFormated('long')}}</p>
        </td>
    </tr>
    <tr class="order-information">
        <td>
            {{if order.getEmailCustomerNote()}}
            <table cellspacing="0" cellpadding="0" class="message-container">
                <tr>
                    <td>{{var order.getEmailCustomerNote()}}</td>
                </tr>
            </table>
            {{/if}}
            {{layout handle="sales_email_order_items" order=$order}}
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="address-details">
                        <h6>Facturer à :</h6>
                        <p><span class="no-link">{{var order.getBillingAddress().format('html')}}</span></p>
                    </td>
                    {{depend order.getIsNotVirtual()}}
                    <td class="address-details">
                        <h6>Envoyer à :</h6>
                        <p><span class="no-link">{{var order.getShippingAddress().format('html')}}</span></p>
                    </td>
                    {{/depend}}
                </tr>
                <tr>
                    {{depend order.getIsNotVirtual()}}
                    <td class="method-info">
                        <h6>Méthode d'envoi :</h6>
                        <p>{{var order.shipping_description}}</p>
                    </td>
                    {{/depend}}
                    <td class="method-info">
                         <h6>Méthode de paiement :</h6>
                        {{var payment_html}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$newExpeditionGuest =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="email-heading">
                        <h1>Merci pour votre commande de  {{var store.getFrontendName()}}.</h1>
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                    </td>
                    <td class="store-info">
                          <h4> Questions de commande?</h4>
                        <p>
                            {{depend store_phone}}
                            <b>Appelez nous : </b>
                            <a href="tel:{{var phone}}">{{var store_phone}}</a><br>
                            {{/depend}}
                            {{depend store_hours}}
                            <span class="no-link">{{var store_hours}}</span><br>
                            {{/depend}}
                            {{depend store_email}}
                            <b>Email : </b> <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{/depend}}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="order-details">
            <h3>Votre livraison <span class="no-link">#{{var shipment.increment_id}}</span></h3>
            <p>Commande <span class="no-link">#{{var order.increment_id}}</span></p>
        </td>
    </tr>
    <tr class="order-information">
        <td>
            {{if comment}}
            <table cellspacing="0" cellpadding="0" class="message-container">
                <tr>
                    <td>{{var comment}}</td>
                </tr>
            </table>
            {{/if}}
            {{layout handle="sales_email_order_shipment_items" shipment=$shipment order=$order}}
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="address-details">
                         <h6>Facturer à :</h6>
                        <p><span class="no-link">{{var order.billing_address.format('html')}}</span></p>
                    </td>
                    {{depend order.getIsNotVirtual()}}
                    <td class="address-details">
                        <h6>Envoyer à :</h6>
                        <p><span class="no-link">{{var order.shipping_address.format('html')}}</span></p>
                    </td>
                    {{/depend}}
                </tr>
                <tr>
                    {{depend order.getIsNotVirtual()}}
                    <td class="method-info">
                        <h6>Méthode d'envoi :</h6>
                        <p>{{var order.shipping_description}}</p>
                    </td>
                    {{/depend}}
                    <td class="method-info">
                        <h6>Méthode de paiement :</h6>
                        {{var payment_html}}
                    </td>
                </tr>
            </table>
            {{block type='core/template' area='frontend' template='email/order/shipment/track.phtml' shipment=$shipment order=$order}}
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$newFacture =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="email-heading">
                        <h1>Merci pour votre commande de {{var store.getFrontendName()}}.</h1>
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                    </td>
                    <td class="store-info">
                        <h4>Questions de commande?</h4>
                        <p>
                            {{depend store_phone}}
                            <b>Appelez nous : </b>
                            <a href="tel:{{var phone}}">{{var store_phone}}</a><br>
                            {{/depend}}
                            {{depend store_hours}}
                            <span class="no-link">{{var store_hours}}</span><br>
                            {{/depend}}
                            {{depend store_email}}
                            <b>Email : </b> <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{/depend}}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="order-details">
            <h3>Votre facture <span class="no-link">#{{var invoice.increment_id}}</span></h3>
            <p>Commande <span class="no-link">#{{var order.increment_id}}</span></p>
        </td>
    </tr>
    <tr class="order-information">
        <td>
            {{if comment}}
            <table cellspacing="0" cellpadding="0" class="message-container">
                <tr>
                    <td>{{var comment}}</td>
                </tr>
            </table>
            {{/if}}
            {{layout area="frontend" handle="sales_email_order_invoice_items" invoice=$invoice order=$order}}
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="address-details">
                        <h6>Facturer à :</h6>
                        <p><span class="no-link">{{var order.billing_address.format('html')}}</span></p>
                    </td>
                    {{depend order.getIsNotVirtual()}}
                    <td class="address-details">
                        <h6>Envoyer à :</h6>
                        <p><span class="no-link">{{var order.shipping_address.format('html')}}</span></p>
                    </td>
                    {{/depend}}
                </tr>
                <tr>
                    {{depend order.getIsNotVirtual()}}
                    <td class="method-info">
                        <h6>Méthode d'envoi :</h6>
                        <p>{{var order.shipping_description}}</p>
                    </td>
                    {{/depend}}
                    <td class="method-info">
                        <h6>Méthode de paiement :</h6>
                        {{var payment_html}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$newFactureGuest =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="email-heading">
                       <h1>Merci pour votre commande de {{var store.getFrontendName()}}.</h1>
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                    </td>
                    <td class="store-info">
                        <h4>Questions de commande?</h4>
                        <p>
                            {{depend store_phone}}
                            <b>Appelez nous : </b>
                            <a href="tel:{{var phone}}">{{var store_phone}}</a><br>
                            {{/depend}}
                            {{depend store_hours}}
                            <span class="no-link">{{var store_hours}}</span><br>
                            {{/depend}}
                            {{depend store_email}}
                            <b>Email : </b> <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{/depend}}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="order-details">
            <h3>Votre facture <span class="no-link">#{{var invoice.increment_id}}</span></h3>
            <p>Commande <span class="no-link">#{{var order.increment_id}}</span></p>
        </td>
    </tr>
    <tr class="order-information">
        <td>
            {{if comment}}
            <table cellspacing="0" cellpadding="0" class="message-container">
                <tr>
                    <td>{{var comment}}</td>
                </tr>
            </table>
            {{/if}}
            {{layout area="frontend" handle="sales_email_order_invoice_items" invoice=$invoice order=$order}}
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="address-details">
                        <h6>Facturer à :</h6>
                        <p><span class="no-link">{{var order.billing_address.format('html')}}</span></p>
                    </td>
                    {{depend order.getIsNotVirtual()}}
                    <td class="address-details">
                        <h6>Envoyer à :</h6>
                        <p><span class="no-link">{{var order.shipping_address.format('html')}}</span></p>
                    </td>
                    {{/depend}}
                </tr>
                <tr>
                    {{depend order.getIsNotVirtual()}}
                    <td class="method-info">
                        <h6>Méthode d'envoi :</h6>
                        <p>{{var order.shipping_description}}</p>
                    </td>
                    {{/depend}}
                    <td class="method-info">
                        <h6>Méthode de paiement :</h6>
                        {{var payment_html}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$newLivraison =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="email-heading">
                        <h1>Merci pour votre commande de {{var store.getFrontendName()}}.</h1>
                        <p>Vous pouvez vérifier l'état de votre commande en vous <a href="{{store url='customer/account/'}}">connectant à votre compte</a>.</p>
                    </td>
                    <td class="store-info">
                        <h4>Questions de commande?</h4>
                        <p>
                            {{depend store_phone}}
                            <b>Appelez nous : </b>
                            <a href="tel:{{var phone}}">{{var store_phone}}</a><br>
                            {{/depend}}
                            {{depend store_hours}}
                            <span class="no-link">{{var store_hours}}</span><br>
                            {{/depend}}
                            {{depend store_email}}
                            <b>Email : </b> <a href="mailto:{{var store_email}}">{{var store_email}}</a>
                            {{/depend}}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="order-details">
            <h3>Votre livraison <span class="no-link">#{{var shipment.increment_id}}</span></h3>
            <p>Commande <span class="no-link">#{{var order.increment_id}}</span></p>
        </td>
    </tr>
    <tr class="order-information">
        <td>
            {{if comment}}
            <table cellspacing="0" cellpadding="0" class="message-container">
                <tr>
                    <td>{{var comment}}</td>
                </tr>
            </table>
            {{/if}}
            {{layout handle="sales_email_order_shipment_items" shipment=$shipment order=$order}}
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="address-details">
                        <h6>Facturer à :</h6>
                        <p><span class="no-link">{{var order.billing_address.format('html')}}</span></p>
                    </td>
                    {{depend order.getIsNotVirtual()}}
                    <td class="address-details">
                        <h6>Envoyer à :</h6>
                        <p><span class="no-link">{{var order.shipping_address.format('html')}}</span></p>
                    </td>
                    {{/depend}}
                </tr>
                <tr>
                    {{depend order.getIsNotVirtual()}}
                    <td class="method-info">
                        <h6>Méthode d'envoi :</h6>
                        <p>{{var order.shipping_description}}</p>
                    </td>
                    {{/depend}}
                    <td class="method-info">
                        <h6>Méthode de paiement :</h6>
                        {{var payment_html}}
                    </td>
                </tr>
            </table>
            {{block type='core/template' area='frontend' template='email/order/shipment/track.phtml' shipment=$shipment order=$order}}
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$listeEnvie =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="action-content">
                        <h1>Salut, jetez un œil à ma liste de {{var store.getFrontendName()}}.</h1>
                        <table cellspacing="0" cellpadding="0" class="message-container">
                            <tr>
                                <td>{{var message}}</td>
                            </tr>
                        </table>
                        {{var items}}
                        {{depend salable}}
                        <p><strong><a href="{{var addAllLink}}">Ajouter tous les articles au panier</a></strong> |
                        {{/depend}}
                        <strong><a href="{{var viewOnSiteLink}}">Voir tous les articles de la liste</a></strong></p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$passwordRappel =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h1>{{htmlescape var=$customer.name}},</h1>
            <p><strong>Votre nouveau mot de passe est : </strong> {{htmlescape var=$customer.password}}</p>
            <p>Vous pouvez modifier votre mot de passe à tout moment en vous connectant à <a href="{{store url='customer/account/'}}"> votre compte</a>.</p>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;
//
$abonnementNewsletter =
    <<<HTML
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
            <h1>Merci de vous être abonné.</h1>
            <p>Pour commencer à recevoir la newsletter, vous devez d'abord confirmer votre abonnement : </p>
            <table cellspacing="0" cellpadding="0" class="action-button" >
                <tr>
                    <td>
                        <a href="{{var subscriber.getConfirmationLink()}}"><span>confirmer votre abonnement</span></a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
HTML;


$emails = [
    [

        'template_code'           => 'Email - En-tête [fr_FR]',
        'template_text'           => $enTete,
        'template_type'           => 1,
        'template_subject'        => 'Email - En-tête',
        'orig_template_code'      => 'design_email_header',
        'orig_template_variables' => '',
        'pathconfiguration'       => 'design/email/header',

    ],
    [

        'template_code'           => 'Email - pied de page [fr_FR]',
        'template_text'           => $piedPage,
        'template_type'           => 1,
        'template_subject'        => 'Email - pied de page',
        'orig_template_code'      => 'design_email_footer',
        'orig_template_variables' => '',
        'pathconfiguration'       => 'design/email/footer',

    ],
    [

        'template_code'           => 'Nouveau compte [fr_FR]',
        'template_text'           => $nvCompte,
        'template_type'           => 2,
        'template_subject'        => 'Bienvenue, {{var customer.name}}!',
        'orig_template_code'      => 'customer_create_account_email_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","htmlescape var=$customer.name":"Customer Name","store url=\"customer/account/\"":"Customer Account Url","var customer.email":"Customer Email","htmlescape var=$customer.password":"Customer Password"}',
        'pathconfiguration'       => 'customer/create_account/email_template',

    ],
    [

        'template_code'           => 'Confirmation : nouveau compte confirmé [fr_FR]',
        'template_text'           => $nvCompteConfirme,
        'template_type'           => 2,
        'template_subject'        => 'Bienvenue, {{var customer.name}}!',
        'orig_template_code'      => 'customer_create_account_email_confirmed_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$customer.name":"Customer Name","store url=\"customer/account/\"":"Customer Account Url"}',
        'pathconfiguration'       => 'customer/create_account/email_confirmed_template',

    ],
    [

        'template_code'           => 'Confirmation : nouvelle clé de confirmation de compte [fr_FR]',
        'template_text'           => $nvCleConfirme,
        'template_type'           => 2,
        'template_subject'        => 'Confirmation de compte pour {{var customer.name}}',
        'orig_template_code'      => 'customer_create_account_email_confirmation_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","store url=\"customer/account/\"":"Customer Account Url","htmlescape var=$customer.name":"Customer Name","var customer.email":"Customer Email","store url=\"customer/account/confirm/\" _query_id=$customer.id _query_key=$customer.confirmation _query_back_url=$back_url":"Confirmation Url","htmlescape var=$customer.password":"Customer password"}',
        'pathconfiguration'       => 'customer/create_account/email_confirmation_template',

    ],
    [

        'template_code'           => 'Souscription à la newsletter réussie [fr_FR]',
        'template_text'           => $souscriptionNewsletter,
        'template_type'           => 2,
        'template_subject'        => 'Souscription à la newsletter réussie',
        'orig_template_code'      => 'newsletter_subscription_success_email_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","htmlescape var=$customer.name":"Customer Name","store url=\"customer/account/\"":"Customer Account Url","var customer.email":"Customer Email","htmlescape var=$customer.password":"Customer Password"}',
        'pathconfiguration'       => 'newsletter/subscription/success_email_template',

    ],
    [

        'template_code'           => 'Produit : alerte de disponibilité de produit [fr_FR]',
        'template_text'           => $alertDispoProduct,
        'template_type'           => 2,
        'template_subject'        => 'Alerte de disponibilité de produit',
        'orig_template_code'      => 'catalog_productalert_email_stock_template',
        'orig_template_variables' => '{"var customerName":"Customer Name","var alertGrid":"Alert Data Grid"}',
        'pathconfiguration'       => 'catalog/productalert/email_stock_template',

    ],
    [

        'template_code'           => 'Produit : alerte de prix produit [fr_FR]',
        'template_text'           => $alertPrixProduct,
        'template_type'           => 2,
        'template_subject'        => 'Alerte de prix produit',
        'orig_template_code'      => 'catalog_productalert_email_price_template',
        'orig_template_variables' => '{"var customerName":"Customer Name","var alertGrid":"Alert Data Grid"}',
        'pathconfiguration'       => 'catalog/productalert/email_price_template',

    ],
    [

        'template_code'           => 'Devise : alertes de mise à jour des devises [fr_FR]',
        'template_text'           => $alertUpDateDevises,
        'template_type'           => 1,
        'template_subject'        => 'Alertes de mise à jour des devises',
        'orig_template_code'      => 'currency_import_error_email_template',
        'orig_template_variables' => '{"var warnings":"Currency Update Warnings"}',
        'pathconfiguration'       => 'currency/import/error_email_template',

    ],
    [

        'template_code'           => 'Logs : alertes de nettoyage de logs [fr_FR]',
        'template_text'           => $alertNettoyageLogs,
        'template_type'           => 1,
        'template_subject'        => 'Alertes de nettoyage de logs',
        'orig_template_code'      => 'system_log_error_email_template',
        'orig_template_variables' => '{"var warnings":"Log Cleanup Warnings"}',
        'pathconfiguration'       => 'system/log/error_email_template',

    ],
    [

        'template_code'           => 'Changement de statut de jeton [fr_FR]',
        'template_text'           => $changStatutJeton,
        'template_type'           => 2,
        'template_subject'        => 'Bienvenue, {{var name}}',
        'orig_template_code'      => 'oauth_email_template',
        'orig_template_variables' => '{"htmlescape var=$userName":"User name","var $applicationName":"Application name","var $status":"Token new status"}',
        'pathconfiguration'       => 'oauth/email/template',

    ],
    [

        'template_code'           => 'Désabonnement de la newsletter réussi [fr_FR]',
        'template_text'           => $desabonnementNewsletter,
        'template_type'           => 2,
        'template_subject'        => 'Désabonnement de la newsletter réussi',
        'orig_template_code'      => 'newsletter_subscription_un_email_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$customer.name":"Customer Name","var subscriber.getConfirmationLink()":"Subscriber Confirmation Url"}',
        'pathconfiguration'       => 'newsletter/subscription/un_email_template',

    ],
    [

        'template_code'           => 'Produit : envoyer le produit à un ami [fr_FR]',
        'template_text'           => $envoiProdAmi,
        'template_type'           => 2,
        'template_subject'        => 'Bienvenue, {{var name}}',
        'orig_template_code'      => 'sendfriend_email_template',
        'orig_template_variables' => '{"htmlescape var=$name":"Recipient Name","var email":"Recipient Email address","var product_url":"Url for Product","var product_name":"Product Name","var product_image":"Url for product small image (75 px)","var sender_name":"Sender name","var sender_email":"Sender email","var message":"Sender Message"}',
        'pathconfiguration'       => 'sendfriend/email/template',

    ],
    [

        'template_code'           => 'Erreur de cron pour les alertes produits [fr_FR ]',
        'template_text'           => $erreurCron,
        'template_type'           => 2,
        'template_subject'        => 'Erreur de cron pour les alertes produits',
        'orig_template_code'      => 'catalog_productalert_cron_error_email_template',
        'orig_template_variables' => '{"var warnings":"Warnings"}',
        'pathconfiguration'       => 'catalog/productalert_cron/error_email_template',

    ],
    [

        'template_code'           => 'Paiement : erreur lors du paiement [fr_FR]',
        'template_text'           => $erreurPaiement,
        'template_type'           => 2,
        'template_subject'        => 'Erreur lors du paiement',
        'orig_template_code'      => 'checkout_payment_failed_template',
        'orig_template_variables' => '{"var warnings":"Warnings"}',
        'pathconfiguration'       => 'checkout/payment_failed/template',

    ],
    [

        'template_code'           => 'Contact : formulaire de contact [fr_FR]',
        'template_text'           => $formContact,
        'template_type'           => 1,
        'template_subject'        => 'Formulaire de contact',
        'orig_template_code'      => 'contacts_email_email_template',
        'orig_template_variables' => '{"var data.name":"Sender Name","var data.email":"Sender Email","var data.telephone":"Sender Telephone","var data.comment":"Comment"}',
        'pathconfiguration'       => 'contacts/email/email_template',

    ],
    [

        'template_code'           => 'Le plan du site a généré des alertes [fr_FR]',
        'template_text'           => $siteMapAlerte,
        'template_type'           => 1,
        'template_subject'        => 'Le plan du site a généré des alertes',
        'orig_template_code'      => 'sitemap_generate_error_email_template',
        'orig_template_variables' => '{"var warnings":"Sitemap Generate Warnings"}',
        'pathconfiguration'       => 'sitemap/generate/error_email_template',

    ],
    [

        'template_code'           => 'Ventes : mise à jour de commande [fr_FR]',
        'template_text'           => $updateCommande,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Commande # {{var order.increment_id}} mise à jour',
        'orig_template_code'      => 'sales_email_order_comment_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$order.getCustomerName()":"Customer Name","var order.increment_id":"Order Id","var order.getStatusLabel()":"Order Status","store url=\"customer/account/\"":"Customer Account Url","var comment":"Order Comment","var store.getFrontendName()":"Store Name"}',
        'pathconfiguration'       => 'sales_email/order_comment/template',

    ],
    [

        'template_code'           => 'Ventes : mise à jour de commande pour un invité [fr_FR]',
        'template_text'           => $updatCommandeGuest,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Commande # {{var order.increment_id}} mise à jour',
        'orig_template_code'      => 'sales_email_order_comment_guest_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$billing.getName()":"Guest Customer Name","var order.increment_id":"Order Id","var order.getStatusLabel()":"Order Status","var comment":"Order Comment","var store.getFrontendName()":"Store Name"}',
        'pathconfiguration'       => 'sales_email/order_comment/guest_template',
    ],
    [

        'template_code'           => 'Ventes : mise à jour de facture [fr_FR]',
        'template_text'           => $updatFacture,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Facture# {{var invoice.increment_id}} mis à jour',
        'orig_template_code'      => 'sales_email_invoice_comment_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$order.getCustomerName()":"Customer Name","var order.increment_id":"Order Id","var order.getStatusLabel()":"Order Status","store url=\"customer/account/\"":"Customer Account Url","var comment":"Invoice Comment","var store.getFrontendName()":"Store Name"}',
        'pathconfiguration'       => 'sales_email/invoice_comment/template',
    ],
    [

        'template_code'           => 'Ventes : mise à jour de facture pour un invité [fr_FR]',
        'template_text'           => $updatFactureGuest,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Facture# {{var invoice.increment_id}} mis à jour',
        'orig_template_code'      => 'sales_email_invoice_comment_guest_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$billing.getName()":"Guest Customer Name","var order.increment_id":"Order Id","var order.getStatusLabel()":"Order Status","var comment":"Invoice Comment","var store.getFrontendName()":"Store Name"}',
        'pathconfiguration'       => 'sales_email/invoice_comment/guest_template',
    ],
    [

        'template_code'           => "Ventes : mise à jour de l'avoir [fr_FR]",
        'template_text'           => $updatAvoir,
        'template_type'           => 2,
        'template_subject'        => "{{var store.getFrontendName()}} : Mise à jour# {{var creditmemo.increment_id}} de l'avoir",
        'orig_template_code'      => 'sales_email_creditmemo_comment_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$order.getCustomerName()":"Customer Name","var order.increment_id":"Order Id","var order.getStatusLabel()":"Order Status","store url=\"customer/account/\"":"Customer Account Url","var comment":"Credit Memo Comment","var store.getFrontendName()":"Store Name"}',
        'pathconfiguration'       => 'sales_email/creditmemo_comment/template',
    ],
    [

        'template_code'           => "Ventes : mise à jour de l'avoir pour un invité [fr_FR]",
        'template_text'           => $updatAvoirGuest,
        'template_type'           => 2,
        'template_subject'        => "{{var store.getFrontendName()}} : Mise à jour# {{var creditmemo.increment_id}} de l'avoir",
        'orig_template_code'      => 'sales_email_creditmemo_comment_guest_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$billing.getName()":"Guest Customer Name","var order.increment_id":"Order Id","var order.getStatusLabel()":"Order Status","var comment":"Credit Memo Comment","var store.getFrontendName()":"Store Name"}',
        'pathconfiguration'       => 'sales_email/creditmemo_comment/guest_template',
    ],
    [

        'template_code'           => "Ventes : mise à jour de l'expédition [fr_FR]",
        'template_text'           => $updatExpedition,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Mise à jour# {{var shipment.increment_id}} livraison',
        'orig_template_code'      => 'sales_email_shipment_comment_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$order.getCustomerName()":"Customer Name","var order.increment_id":"Order Id","var order.getStatusLabel()":"Order Status","store url=\"customer/account/\"":"Customer Account Url","var comment":"Order Comment","var store.getFrontendName()":"Store Name"}',
        'pathconfiguration'       => 'sales_email/shipment_comment/template',
    ],
    [

        'template_code'           => "Ventes : mise à jour de l'expédition pour un invité [fr_FR]",
        'template_text'           => $updatExpeditionGuest,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Mise à jour# {{var shipment.increment_id}} livraison',
        'orig_template_code'      => 'sales_email_shipment_comment_guest_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$billing.getName()":"Guest Customer Name","var order.increment_id":"Order Id","var order.getStatusLabel()":"Order Status","var comment":"Order Comment","var store.getFrontendName()":"Store Name"}',
        'pathconfiguration'       => 'sales_email/shipment_comment/guest_template',
    ],
    [

        'template_code'           => "Mot de passe admin oublié [fr_FR]",
        'template_text'           => $passwordAdminForgot,
        'template_type'           => 2,
        'template_subject'        => 'Réinitialiser le mot de passe confirmation pour {{var user.name}}',
        'orig_template_code'      => 'admin_emails_forgot_email_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$user.name":"Admin Name","store url=\"adminhtml/index/resetpassword/\" _query_id=$user.id _query_token=$user.rp_token":"Reset Password URL","store url=\"adminhtml/system_account/\"":"Admin Account Url"}',
        'pathconfiguration'       => 'admin/emails/forgot_email_template',
    ],
    [

        'template_code'           => "Mot de passe oublié [fr_FR]",
        'template_text'           => $passwordForgot,
        'template_type'           => 2,
        'template_subject'        => 'Réinitialiser le mot de passe confirmation pour {{var customer.name}}',
        'orig_template_code'      => 'customer_password_forgot_email_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$customer.name":"Customer Name","store url=\"customer/account/resetpassword/\" _query_id=$customer.id _query_token=$customer.rp_token":"Reset Password URL"}',
        'pathconfiguration'       => 'customer/password/forgot_email_template',
    ],
    [

        'template_code'           => "Ventes : nouvel avoir [fr_FR]",
        'template_text'           => $newAvoir,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Avoir # {{var creditmemo.increment_id}} de la commande # {{var order.increment_id}}',
        'orig_template_code'      => 'sales_email_creditmemo_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$order.getCustomerName()":"Customer Name","var store.getFrontendName()":"Store Name","store url=\"customer/account/\"":"Customer Account Url","var creditmemo.increment_id":"Credit Memo Id","var order.increment_id":"Order Id","var order.billing_address.format(\'html\')":"Billing Address","payment_html":"Payment Details","var order.shipping_address.format(\'html\')":"Shipping Address","var order.shipping_description":"Shipping Description","layout handle=\"sales_email_order_creditmemo_items\" creditmemo=$creditmemo order=$order":"Credit Memo Items Grid","var comment":"Credit Memo Comment"}',
        'pathconfiguration'       => 'sales_email/creditmemo/template',
    ],
    [

        'template_code'           => "Ventes : nouvel avoir pour invité [fr_FR]",
        'template_text'           => $newAvoirGuest,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Avoir # {{var creditmemo.increment_id}} de la commande # {{var order.increment_id}}',
        'orig_template_code'      => 'sales_email_creditmemo_guest_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$billing.getName()":"Guest Customer Name (Billing)","var store.getFrontendName()":"Store Name","var creditmemo.increment_id":"Credit Memo Id","var order.increment_id":"Order Id","var order.billing_address.format(\'html\')":"Billing Address","var payment_html":"Payment Details","var order.shipping_address.format(\'html\')":"Shipping Address","var order.shipping_description":"Shipping Description","layout handle=\"sales_email_order_creditmemo_items\" creditmemo=$creditmemo order=$order":"Credit Memo Items Grid","var comment":"Credit Memo Comment"}',
        'pathconfiguration'       => 'sales_email/creditmemo/guest_template',
    ],
    [

        'template_code'           => "Ventes : nouvelle commande [fr_FR]",
        'template_text'           => $newCommande,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Nouvelle commande # {{var order.increment_id}}',
        'orig_template_code'      => 'sales_email_order_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$order.getCustomerName()":"Customer Name","var store.getFrontendName()":"Store Name","store url=\"customer/account/\"":"Customer Account Url","var order.increment_id":"Order Id","var order.getCreatedAtFormated(\'long\')":"Order Created At (datetime)","var order.getBillingAddress().format(\'html\')":"Billing Address","var payment_html":"Payment Details","var order.getShippingAddress().format(\'html\')":"Shipping Address","var order.getShippingDescription()":"Shipping Description","layout handle=\"sales_email_order_items\" order=$order":"Order Items Grid","var order.getEmailCustomerNote()":"Email Order Note"}',
        'pathconfiguration'       => 'sales_email/order/template',
    ],
    [
        'template_code'           => "Ventes : nouvelle commande pour un invité [fr_FR]",
        'template_text'           => $newCommandeGuest,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Nouvelle commande # {{var order.increment_id}}',
        'orig_template_code'      => 'sales_email_order_guest_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$order.getCustomerName()":"Customer Name","var store.getFrontendName()":"Store Name","store url=\"customer/account/\"":"Customer Account Url","var order.increment_id":"Order Id","var order.getCreatedAtFormated(\'long\')":"Order Created At (datetime)","var order.getBillingAddress().format(\'html\')":"Billing Address","var payment_html":"Payment Details","var order.getShippingAddress().format(\'html\')":"Shipping Address","var order.getShippingDescription()":"Shipping Description","layout handle=\"sales_email_order_items\" order=$order":"Order Items Grid","var order.getEmailCustomerNote()":"Email Order Note"}',
        'pathconfiguration'       => 'sales_email/order/guest_template',
    ],
    [
        'template_code'           => "Ventes : nouvelle expédition pour un invité [fr_FR]",
        'template_text'           => $newExpeditionGuest,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Livraison# {{var shipment.increment_id}} de la commande # {{var order.increment_id}}',
        'orig_template_code'      => 'sales_email_shipment_guest_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$billing.getName()":"Guest Customer Name","var store.getFrontendName()":"Store Name","var shipment.increment_id":"Shipment Id","var order.increment_id":"Order Id","var order.billing_address.format(\'html\')":"Billing Address","var payment_html":"Payment Details","var order.shipping_address.format(\'html\')":"Shipping Address","var order.shipping_description":"Shipping Description","layout handle=\"sales_email_order_shipment_items\" shipment=$shipment order=$order":"Shipment Items Grid","block type=\'core/template\' area=\'frontend\' template=\'email/order/shipment/track.phtml\' shipment=$shipment order=$order":"Shipment Track Details","var comment":"Shipment Comment"}',
        'pathconfiguration'       => 'sales_email/shipment/guest_template',
    ],
    [
        'template_code'           => "Ventes : nouvelle facture [fr_FR]",
        'template_text'           => $newFacture,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Facture# {{var invoice.increment_id}} de la commande # {{var order.increment_id}}',
        'orig_template_code'      => 'sales_email_invoice_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$order.getCustomerName()":"Customer Name","var store.getFrontendName()":"Store Name","store url=\"customer/account/\"":"Customer Account Url","var invoice.increment_id":"Invoice Id","var order.increment_id":"Order Id","var order.billing_address.format(\'html\')":"Billing Address","var payment_html":"Payment Details","var order.shipping_address.format(\'html\')":"Shipping Address","var order.shipping_description":"Shipping Description","layout area=\"frontend\" handle=\"sales_email_order_invoice_items\" invoice=$invoice order=$order":"Invoice Items Grid","var comment":"Invoice Comment"}',
        'pathconfiguration'       => 'sales_email/invoice/template',
    ],
    [
        'template_code'           => "Ventes : nouvelle facture pour un invité [fr_FR]",
        'template_text'           => $newFactureGuest,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Facture# {{var invoice.increment_id}} de la commande # {{var order.increment_id}}',
        'orig_template_code'      => 'sales_email_invoice_guest_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$order.getCustomerName()":"Customer Name","var store.getFrontendName()":"Store Name","store url=\"customer/account/\"":"Customer Account Url","var invoice.increment_id":"Invoice Id","var order.increment_id":"Order Id","var order.billing_address.format(\'html\')":"Billing Address","var payment_html":"Payment Details","var order.shipping_address.format(\'html\')":"Shipping Address","var order.shipping_description":"Shipping Description","layout area=\"frontend\" handle=\"sales_email_order_invoice_items\" invoice=$invoice order=$order":"Invoice Items Grid","var comment":"Invoice Comment"}',
        'pathconfiguration'       => 'sales_email/invoice/guest_template',
    ],
    [
        'template_code'           => "Ventes : nouvelle livraison [fr_FR]",
        'template_text'           => $newLivraison,
        'template_type'           => 2,
        'template_subject'        => '{{var store.getFrontendName()}} : Livraison# {{var shipment.increment_id}} de la commande # {{var order.increment_id}}',
        'orig_template_code'      => 'sales_email_shipment_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$order.getCustomerName()":"Customer Name","var store.getFrontendName()":"Store Name","store url=\"customer/account/\"":"Customer Account Url","var shipment.increment_id":"Shipment Id","var order.increment_id":"Order Id","var order.billing_address.format(\'html\')":"Billing Address","var payment_html":"Payment Details","var order.shipping_address.format(\'html\')":"Shipping Address","var order.shipping_description":"Shipping Description","layout handle=\"sales_email_order_shipment_items\" shipment=$shipment order=$order":"Shipment Items Grid","block type=\'core/template\' area=\'frontend\' template=\'email/order/shipment/track.phtml\' shipment=$shipment order=$order":"Shipment Track Details","var comment":"Shipment Comment"}',
        'pathconfiguration'       => 'sales_email/shipment/template',
    ],
    [
        'template_code'           => "Envie : partager la liste d'envies [fr_FR]",
        'template_text'           => $listeEnvie,
        'template_type'           => 2,
        'template_subject'        => 'Jetez un œil sur la liste de {{var customer.name}}',
        'orig_template_code'      => 'wishlist_email_email_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","var message":"Wishlist Message","var items":"Wishlist Items"}',
        'pathconfiguration'       => 'wishlist/email/email_template',
    ],
    [
        'template_code'           => "Rappel de mot de passe [fr_FR]",
        'template_text'           => $passwordRappel,
        'template_type'           => 2,
        'template_subject'        => 'Nouveau mot de passe pour  {{var customer.name}}',
        'orig_template_code'      => 'newsletter_subscription_confirm_email_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","store url=\"customer/account/\"":"Customer Account Url","htmlescape var=$customer.name":"Customer Name","htmlescape var=$customer.password":"Customer New Password"}',
        'pathconfiguration'       => 'customer/password/remind_email_template',
    ],
    [
        'template_code'           => "Confirmation de l'abonnement à la newsletter [fr_FR]",
        'template_text'           => $abonnementNewsletter,
        'template_type'           => 2,
        'template_subject'        => "Confirmation de l'abonnement à la newsletter",
        'orig_template_code'      => 'customer_password_remind_email_template',
        'orig_template_variables' => '{"store url=\"\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$customer.name":"Customer Name","var subscriber.getConfirmationLink()":"Subscriber Confirmation Url"}',
        'pathconfiguration'       => 'newsletter/subscription/confirm_email_template',
    ],

];

foreach ($emails as $email) {
    $_oldEmailTemplate = Mage::getModel('core/email_template');
    $_oldEmailTemplate->loadByCode($email['template_code']);
    if ($_oldEmailTemplate->getId()) {
        $_oldEmailTemplate->delete();
    }

    $template = Mage::getModel('core/email_template')
                    ->setData('template_code', $email['template_code'])
                    ->setData('template_text', $email['template_text'])
                    ->setData('template_type', $email['template_type'])
                    ->setData('template_subject', $email['template_subject'])
                    ->setData('orig_template_code', $email['orig_template_code'])
                    ->setData('orig_template_variables', $email['orig_template_variables'])
                    ->save();

    $this->setConfigData($email['pathconfiguration'], $template->getId(), 'stores', $frenchStoreId);
}

