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

/** @var $this Mage_Core_Model_Resource_Setup */

$sabreStoreFrench = false;
$sabreStoreEnglish = false;

$sabreWebsite = Mage::getModel('core/website')->load('base', 'code');
if ($sabreWebsite->getId()) { // means no configuration done yet

    // change website code etc
    $sabreWebsite->setCode('sabre_fr')
                 ->setName('Sabre.fr')
                 ->setIsDefault(1)
                 ->setSortOrder(1)
                 ->save();

    // get store group and change data
    $sabreStoreGroup = $sabreWebsite->getDefaultGroup();
    $sabreStoreGroup->setName('Catalogue Sabre')
                    ->save();

    // get default store and change data (french)
    $sabreStoreFrench = $sabreWebsite->getDefaultStore();
    $sabreStoreFrench->setCode('sabre_fr_french')
                     ->setName('Français')
                     ->setSortOrder('1')
                     ->setIsActive(1)
                     ->save();

    // create new store (english)
    $sabreStoreEnglish = Mage::getModel('core/store');
    $sabreStoreEnglish->setCode('sabre_fr_english')
                      ->setName('Anglais')
                      ->setSortOrder('1')
                      ->setIsActive(1)
                      ->setGroupId($sabreStoreGroup->getId())
                      ->setWebsiteId($sabreWebsite->getId())
                      ->save();

    // change root category name
    $rootCategory = Mage::getModel('catalog/category')->load($sabreStoreGroup->getRootCategoryId());
    $rootCategory->setData('name', 'ROOT_SABRE')
                 ->save();

}

/**
 * Catalog > Frontend > Product Listing Sort by
 *  Source: adminhtml/system_config_source_catalog_listSort
 *  Default: position
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/frontend/default_sort_by', 'position');

/**
 * Catalog > Frontend > Use Flat Catalog Category
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 */
$this->setConfigData('catalog/frontend/flat_catalog_category', 0); // only 3 categories...

/**
 * Catalog > Frontend > Use Flat Catalog Product
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 */
$this->setConfigData('catalog/frontend/flat_catalog_product', 1);

/**
 * Catalog > Frontend > Products per Page on Grid Default Value
 *  Default: 12
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/frontend/grid_per_page', 12);

/**
 * Catalog > Frontend > Products per Page on Grid Allowed Values
 *  Default: 12,24,36
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/frontend/grid_per_page_values', 12);

/**
 * Catalog > Frontend > Allow All Products per Page
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/frontend/list_allow_all', 0);

/**
 * Catalog > Frontend > List Mode
 *  Source: adminhtml/system_config_source_catalog_listMode
 *  Default: grid-list
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/frontend/list_mode', 'grid');

/**
 * Catalog > Frontend > Products per Page on List Default Value
 *  Default: 10
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/frontend/list_per_page', 1);

/**
 * Catalog > Frontend > Products per Page on List Allowed Values
 *  Default: 5,10,15,20,25
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/frontend/list_per_page_values', 1);

/**
 * Catalog > Frontend > Allow Dynamic Media URLs in Products and Categories
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/frontend/parse_url_directives', 1);

/**
 * Catalog > Layered Navigation > Display Product Count
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/layered_navigation/display_product_count', 0);

/**
 * Catalog > Layered Navigation > Maximum Number of Price Intervals
 *  Default: 10
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/layered_navigation/price_range_max_intervals', 1); // disable it?

/**
 * Catalog > Price > Catalog Price Scope
 *  Source: adminhtml/system_config_source_price_scope
 *  Default:
 */
$this->setConfigData('catalog/price/scope', '1'); // means website

/**
 * Catalog > Product Alerts > Allow Alert When Product Price Changes
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/productalert/allow_price', 0);

/**
 * Catalog > Product Alerts > Allow Alert When Product Comes Back in Stock
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on website view
 */
$this->setConfigData('catalog/productalert/allow_stock', 0);

/**
 * Catalog > Product Reviews > Allow Guests to Write Reviews
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on website view
 */
$this->setConfigData('catalog/review/allow_guest', 0);

/**
 * Catalog > Catalog Search > Search Type
 *  Source: adminhtml/system_config_source_catalog_search_type
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/search/search_type', 2); // fulltext

/**
 * Catalog > Catalog Search > Show Autocomplete Results Count
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/search/show_autocomplete_results_count', 0);

/**
 * Catalog > Search Engine Optimizations > Use Canonical Link Meta Tag For Categories
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/seo/category_canonical_tag', 1);

/**
 * Catalog > Search Engine Optimizations > Use Canonical Link Meta Tag For Products
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/seo/product_canonical_tag', 1);

/**
 * Catalog > Search Engine Optimizations > Use Categories Path for Product URLs
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/seo/product_use_categories', 1);

/**
 * Catalog > Search Engine Optimizations > Create Permanent Redirect for URLs if URL Key Changed
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/seo/save_rewrites_history', 1);

/**
 * Catalog > Search Engine Optimizations > Popular Search Terms
 *  Source: adminhtml/system_config_source_enabledisable
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('catalog/seo/search_terms', 0);

/**
 * Inventory > Product Stock Options > Automatically Return Credit Memo Item to Stock
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 */
$this->setConfigData('cataloginventory/item_options/auto_return', 0);

/**
 * Inventory > Product Stock Options > Manage Stock
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 */
$this->setConfigData('cataloginventory/item_options/manage_stock', 0);

/**
 * Inventory > Stock Options > Display products availability in stock in the frontend
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('cataloginventory/options/display_product_stock_status', 0);

/**
 * Inventory > Stock Options > Display Out of Stock Products
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 */
$this->setConfigData('cataloginventory/options/show_out_of_stock', 1);

/**
 * Checkout > Shopping Cart > After Adding a Product Redirect to Shopping Cart
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('checkout/cart/redirect_to_cart', 0);

/**
 * Checkout > Checkout Options > Require Customer To Be Logged In To Checkout
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('checkout/options/customer_must_be_logged', 0);

/**
 * Checkout > Checkout Options > Enable Terms and Conditions
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('checkout/options/enable_agreements', 1);

/**
 * Checkout > Checkout Options > Allow Guest Checkout
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('checkout/options/guest_checkout', 0);

/**
 * Checkout > Payment Failed Emails > Send Payment Failed Email Copy Method
 *  Source: adminhtml/system_config_source_email_method
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('checkout/payment_failed/copy_method', 'bcc');

/**
 * Content Management > WYSIWYG Options > Use Static URLs for Media Content in WYSIWYG for Catalog
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 */
$this->setConfigData('cms/wysiwyg/use_static_urls_in_catalog', 0);

/**
 * Contacts > Email Options > Send Emails To
 *  Default: hello@example.com
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('contacts/email/recipient_email', '');

/**
 * Customer Configuration > Name and Address Options > Show Date of Birth
 *  Source: adminhtml/system_config_source_nooptreq
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('customer/address/dob_show', '');

/**
 * Customer Configuration > Name and Address Options > Show Gender
 *  Source: adminhtml/system_config_source_nooptreq
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('customer/address/gender_show', '');

/**
 * Customer Configuration > Name and Address Options > Show Middle Name (initial)
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on website view
 */
$this->setConfigData('customer/address/middlename_show', 0);

/**
 * Customer Configuration > Name and Address Options > Prefix Dropdown Options
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('customer/address/prefix_options', 'M.,Mme.');

/**
 * Customer Configuration > Name and Address Options > Show Prefix
 *  Source: adminhtml/system_config_source_nooptreq
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('customer/address/prefix_show', 'opt');

/**
 * Customer Configuration > Name and Address Options > Suffix Dropdown Options
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('customer/address/suffix_options', '');

/**
 * Customer Configuration > Name and Address Options > Show Suffix
 *  Source: adminhtml/system_config_source_nooptreq
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('customer/address/suffix_show', '');

/**
 * Customer Configuration > Name and Address Options > Show Tax/VAT Number
 *  Source: adminhtml/system_config_source_nooptreq
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('customer/address/taxvat_show', '');

/**
 * Customer Configuration > Create New Account Options > Enable Automatic Assignment to Customer Group
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('customer/create_account/auto_group_assign', 0);

/**
 * Customer Configuration > Create New Account Options > Require Emails Confirmation
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on website view
 */
$this->setConfigData('customer/create_account/confirm', 0);

/**
 * Customer Configuration > Create New Account Options > Default Email Domain
 *  Default: example.com
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('customer/create_account/email_domain', 'sabre.fr');

/**
 * Customer Configuration > Create New Account Options > Generate Human-Friendly Customer ID
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 */
$this->setConfigData('customer/create_account/generate_human_friendly_id', 1);

/**
 * Customer Configuration > Create New Account Options > Tax Calculation Based On
 *  Source: adminhtml/system_config_source_customer_address_type
 *  Default: billing
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('customer/create_account/tax_calculation_address_type', 'shipping');

/**
 * Customer Configuration > Create New Account Options > Default Value for Disable Automatic Group Changes Based on VAT ID
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 */
$this->setConfigData('customer/create_account/viv_disable_auto_group_assign_default', 0);

/**
 * Customer Configuration > Login Options > Redirect Customer to Account Dashboard after Logging in
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on website view
 */
$this->setConfigData('customer/startup/redirect_dashboard', 0);

/**
 * Design > Transactional Emails > Logo Image Alt
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/email/logo_alt', 'Sabre');

/**
 * Design > Footer > Copyright
 *  Default: &copy; 2015 Magento Demo Store. All Rights Reserved.
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/footer/copyright', '&copy; Sabre 2015');

/**
 * Design > HTML Head > Default Description
 *  Default: Default Description
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/head/default_description', 'Sabre, la référence des arts de la table haut de gamme, chic et fantaisie. Source d’inspiration de vos tables depuis plus de 15 ans, Sabre conçoit ses couverts à la manière d’une marque de prêt à porter, en proposant des collections riches et variées au grès des saisons et occasions. Composez vous-même vos services sur mesure à partir des milliers de combinaisons possibles disponible sur notre boutique en ligne. Modèles, couleurs, motifs, il existe un service Sabre adapté à chaque occasion, selon vos goûts.');

/**
 * Design > HTML Head > Default Keywords
 *  Default: Magento, Varien, E-commerce
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/head/default_keywords', 'Sabre, sabre paris, couverts sabre, sabre couvert, art de la table, service de table, pois, couverts fantaisie, couverts chics, couverts à pois, couverts sur mesure, couverts haut de gamme, couverts tendances, couverts créatifs, couverts originaux, vaisselle, verrerie, accessoires, motifs, créatif, collection, saison');

/**
 * Design > HTML Head > Default Robots
 *  Source: adminhtml/system_config_source_design_robots
 *  Default: *
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/head/default_robots', 'INDEX,FOLLOW');

/**
 * Design > HTML Head > Default Title
 *  Default: Magento Commerce
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/head/default_title', 'Couverts - Vaisselle - Verrerie');

/**
 * Design > HTML Head > Display Demo Store Notice
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/head/demonotice', 0);

/**
 * Design > HTML Head > Miscellaneous Scripts
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/head/includes', '');

/**
 * Design > HTML Head > Favicon Icon
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/head/shortcut_icon', '');

/**
 * Design > HTML Head > Title Prefix
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/head/title_prefix', 'Sabre Paris |');

/**
 * Design > HTML Head > Title Suffix
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/head/title_suffix', '');

/**
 * Design > Header > Logo Image Alt
 *  Default: Magento Commerce
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/header/logo_alt', 'Sabre');

/**
 * Design > Header > Welcome Text
 *  Default: Default welcome msg!
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/header/welcome', 'Sabre');

/**
 * Design > Package > Current Package Name
 *  Default: rwd
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/package/name', 'sabre');

/**
 * Design > Package >
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/package/ua_regexp', 'a:0:{}');

/**
 * Design > Pagination > Anchor Text for Next
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/pagination/anchor_text_for_next', ''); // no pagination

/**
 * Design > Pagination > Anchor Text for Previous
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/pagination/anchor_text_for_previous', ''); // no pagination

/**
 * Design > Themes > Default
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/theme/default', 'default');

/**
 * Design > Themes >
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/theme/default_ua_regexp', 'a:0:{}');

/**
 * Design > Themes > Layout
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/theme/layout', '');

/**
 * Design > Themes >
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/theme/layout_ua_regexp', 'a:0:{}');

/**
 * Design > Themes > Translations
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/theme/locale', '');

/**
 * Design > Themes > Skin (Images / CSS)
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/theme/skin', '');

/**
 * Design > Themes >
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/theme/skin_ua_regexp', 'a:0:{}');

/**
 * Design > Themes > Templates
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/theme/template', '');

/**
 * Design > Themes >
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('design/theme/template_ua_regexp', 'a:0:{}');

/**
 * Developer > CSS Settings > Merge CSS Files
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('dev/css/merge_css_files', 1);

/**
 * Developer > Debug > Profiler
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('dev/debug/profiler', 0);

/**
 * Developer > Debug > Template Path Hints
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('dev/debug/template_hints', 0);

/**
 * Developer > Debug > Add Block Names to Hints
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('dev/debug/template_hints_blocks', 0);

/**
 * Developer > JavaScript Settings > Merge JavaScript Files
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('dev/js/merge_files', 1);

/**
 * Developer > Log Settings > Enabled
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('dev/log/active', 1);

/**
 * Developer > Developer Client Restrictions > Allowed IPs (comma separated)
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('dev/restrict/allow_ips', '');

/**
 * General > Countries Options > Default Country
 *  Source: adminhtml/system_config_source_country
 *  Default: US
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('general/country/default', 'FR');

/**
 * General > Locale Options > Locale
 *  Source: adminhtml/system_config_source_locale
 *  Default: fr_FR
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('general/locale/code', 'fr_FR');

/**
 * General > Locale Options > First Day of Week
 *  Source: adminhtml/system_config_source_locale_weekdays
 *  Default: 0
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('general/locale/firstday', 1); // Monday

/**
 * General > Locale Options > Timezone
 *  Source: adminhtml/system_config_source_locale_timezone
 *  Default: Europe/Paris
 *
 *   Can be configured on website view
 */
$this->setConfigData('general/locale/timezone', 'Europe/Paris');

/**
 * General > Store Information > Store Contact Address
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('general/store_information/address', "21 avenue de l'europe\n78400 Chatou");

/**
 * General > Store Information > Store Hours of Operation
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('general/store_information/hours', '');

/**
 * General > Store Information > Country
 *  Source: adminhtml/system_config_source_country
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('general/store_information/merchant_country', 'FR');

/**
 * General > Store Information > VAT Number
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('general/store_information/merchant_vat_number', '');

/**
 * General > Store Information > Store Name
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('general/store_information/name', 'Boutique SABRE');

/**
 * General > Store Information > Store Contact Telephone
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('general/store_information/phone', '+331 30 09 50 00');

/**
 * General > Store Information >
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('general/store_information/validate_vat_number', '');

/**
 * Google API > Google Analytics > Account Number
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('google/analytics/account', '');

/**
 * Google API > Google Analytics > Enable
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('google/analytics/active', 0); // disabled because we use our module

/**
 * Persistent Shopping Cart > General Options > Enable Persistence
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on website view
 */
$this->setConfigData('persistent/options/enabled', 1);

/**
 * Promotions > Auto Generated Specific Coupon Codes > Dash Every X Characters
 *  Default:
 */
$this->setConfigData('promo/auto_generated_coupon_codes/dash', '');

/**
 * Promotions > Auto Generated Specific Coupon Codes > Code Format
 *  Source: salesrule/system_config_source_coupon_format
 *  Default: 1
 */
$this->setConfigData('promo/auto_generated_coupon_codes/format', 'alphanum');

/**
 * Promotions > Auto Generated Specific Coupon Codes > Code Length
 *  Default: 12
 */
$this->setConfigData('promo/auto_generated_coupon_codes/length', '12');

/**
 * Promotions > Auto Generated Specific Coupon Codes > Code Prefix
 *  Default:
 */
$this->setConfigData('promo/auto_generated_coupon_codes/prefix', '');

/**
 * Promotions > Auto Generated Specific Coupon Codes > Code Suffix
 *  Default:
 */
$this->setConfigData('promo/auto_generated_coupon_codes/suffix', '');

/**
 * RSS Feeds > Catalog > Top Level Category
 *  Source: adminhtml/system_config_source_enabledisable
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('rss/catalog/category', 0);

/**
 * RSS Feeds > Catalog > New Products
 *  Source: adminhtml/system_config_source_enabledisable
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('rss/catalog/new', 0);

/**
 * RSS Feeds > Catalog > Coupons/Discounts
 *  Source: adminhtml/system_config_source_enabledisable
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('rss/catalog/salesrule', 0);

/**
 * RSS Feeds > Catalog > Special Products
 *  Source: adminhtml/system_config_source_enabledisable
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('rss/catalog/special', 0);

/**
 * RSS Feeds > Catalog > Tags Products
 *  Source: adminhtml/system_config_source_enabledisable
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('rss/catalog/tag', 0);

/**
 * RSS Feeds > Rss Config > Enable RSS
 *  Source: adminhtml/system_config_source_enabledisable
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('rss/config/active', 0);

/**
 * RSS Feeds > Order > Customer Order Status Notification
 *  Source: adminhtml/system_config_source_enabledisable
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('rss/order/status_notified', 0);

/**
 * RSS Feeds > Wishlist > Enable RSS
 *  Source: adminhtml/system_config_source_enabledisable
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('rss/wishlist/active', 0);

/**
 * Sales > Gift Options > Allow Gift Messages for Order Items
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('sales/gift_options/allow_items', 0);

/**
 * Sales > Gift Options > Allow Gift Messages on Order Level
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('sales/gift_options/allow_order', 1);

/**
 * Sales > Invoice and Packing Slip Design > Address
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('sales/identity/address', "SABRE PARIS\n21 avenue de l'Europe,\n78400 Chatou");

/**
 * Sales > Minimum Order Amount > Enable
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('sales/minimum_order/active', 0);

/**
 * Sales Emails > Credit Memo > Send Credit Memo Email Copy Method
 *  Source: adminhtml/system_config_source_email_method
 *  Default: bcc
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('sales_email/creditmemo/copy_method', 'copy');

/**
 * Sales Emails > Credit Memo > Send Credit Memo Email Copy To
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
//$this->setConfigData('sales_email/creditmemo/copy_to', 'boutique.sabre@gmail.com,eshop@sabre.fr');

/**
 * Sales Emails > Credit Memo Comments > Send Credit Memo Comments Email Copy Method
 *  Source: adminhtml/system_config_source_email_method
 *  Default: bcc
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('sales_email/creditmemo_comment/copy_method', 'copy');

/**
 * Sales Emails > Credit Memo Comments > Send Credit Memo Comment Email Copy To
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
//$this->setConfigData('sales_email/creditmemo_comment/copy_to', 'boutique.sabre@gmail.com,eshop@sabre.fr');

/**
 * Sales Emails > Invoice > Send Invoice Email Copy Method
 *  Source: adminhtml/system_config_source_email_method
 *  Default: bcc
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('sales_email/invoice/copy_method', 'copy');

/**
 * Sales Emails > Invoice > Send Invoice Email Copy To
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
//$this->setConfigData('sales_email/invoice/copy_to', 'boutique.sabre@gmail.com,eshop@sabre.fr');

/**
 * Sales Emails > Invoice Comments > Send Invoice Comments Email Copy Method
 *  Source: adminhtml/system_config_source_email_method
 *  Default: bcc
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('sales_email/invoice_comment/copy_method', 'copy');

/**
 * Sales Emails > Invoice Comments > Send Invoice Comment Email Copy To
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
//$this->setConfigData('sales_email/invoice_comment/copy_to', 'boutique.sabre@gmail.com,eshop@sabre.fr');

/**
 * Sales Emails > Order > Send Order Email Copy Method
 *  Source: adminhtml/system_config_source_email_method
 *  Default: bcc
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('sales_email/order/copy_method', 'copy');

/**
 * Sales Emails > Order > Send Order Email Copy To
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
//$this->setConfigData('sales_email/order/copy_to', 'boutique.sabre@gmail.com,eshop@sabre.fr,admin@illusio.fr,sabre.paris@gmail.com');

/**
 * Sales Emails > Order Comments > Send Order Comments Email Copy Method
 *  Source: adminhtml/system_config_source_email_method
 *  Default: bcc
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('sales_email/order_comment/copy_method', 'copy');

/**
 * Sales Emails > Order Comments > Send Order Comment Email Copy To
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
//$this->setConfigData('sales_email/order_comment/copy_to', 'boutique.sabre@gmail.com,eshop@sabre.fr');

/**
 * Sales Emails > Shipment > Send Shipment Email Copy Method
 *  Source: adminhtml/system_config_source_email_method
 *  Default: bcc
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('sales_email/shipment/copy_method', 'copy');

/**
 * Sales Emails > Shipment > Send Shipment Email Copy To
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
//$this->setConfigData('sales_email/shipment/copy_to', 'boutique.sabre@gmail.com,eshop@sabre.fr');

/**
 * Sales Emails > Shipment Comments > Send Shipment Comments Email Copy Method
 *  Source: adminhtml/system_config_source_email_method
 *  Default: bcc
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('sales_email/shipment_comment/copy_method', 'copy');

/**
 * Sales Emails > Shipment Comments > Send Shipment Comment Email Copy To
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
//$this->setConfigData('sales_email/shipment_comment/copy_to', 'boutique.sabre@gmail.com,eshop@sabre.fr');

/**
 * Email to a Friend > Email Templates > Enabled
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('sendfriend/email/enabled', 0);

/**
 * Shipping Settings > Options > Allow Shipping to Multiple Addresses
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on website view
 */
$this->setConfigData('shipping/option/checkout_multiple', 0);

/**
 * Shipping Settings > Origin > City
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('shipping/origin/city', 'Chatou');

/**
 * Shipping Settings > Origin > Country
 *  Source: adminhtml/system_config_source_country
 *  Default: US
 *
 *   Can be configured on website view
 */
$this->setConfigData('shipping/origin/country_id', 'FR');

/**
 * Shipping Settings > Origin > ZIP/Postal Code
 *  Default: 90034
 *
 *   Can be configured on website view
 */
$this->setConfigData('shipping/origin/postcode', '78400');

/**
 * Shipping Settings > Origin > Region/State
 *  Default: 12
 *
 *   Can be configured on website view
 */
$this->setConfigData('shipping/origin/region_id', '260'); // Yvelines

/**
 * Shipping Settings > Origin > Street Address
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('shipping/origin/street_line1', "21 avenue de l'Europe");

/**
 * Shipping Settings > Origin > Street Address Line 2
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('shipping/origin/street_line2', '');

/**
 * System > Notifications > Use HTTPS to Get Feed
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 */
$this->setConfigData('system/adminnotification/use_https', 1);

/**
 * System > Scheduled Backup Settings > Enable Scheduled Backup
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 */
$this->setConfigData('system/backup/enabled', 0);

/**
 * System > Log > Save Log, Days
 *  Default: 180
 */
$this->setConfigData('system/log/clean_after_day', 60);

/**
 * System > Log > Enable Log
 *  Source: log/adminhtml_system_config_source_loglevel
 *  Default: 2
 */
$this->setConfigData('system/log/enable_log', 0);

/**
 * System > Log > Start Time
 *  Default:
 */
$this->setConfigData('system/log/time', '00,00,00');

/**
 * Tax > Calculation Settings > Apply Tax On
 *  Source: adminhtml/system_config_source_tax_apply_on
 *  Default: 0
 *
 *   Can be configured on website view
 */
$this->setConfigData('tax/calculation/apply_tax_on', 1); // Original price only


/**
 * Tax > Calculation Settings > Enable Cross Border Trade
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on website view
 */
$this->setConfigData('tax/calculation/cross_border_trade_enabled', 1);

/**
 * Tax > Calculation Settings > Apply Discount On Prices
 *  Source: tax/system_config_source_priceType
 *  Default: 0
 *
 *   Can be configured on website view
 */
$this->setConfigData('tax/calculation/discount_tax', 1); // Including Tax

/**
 * Tax > Calculation Settings > Catalog Prices
 *  Source: tax/system_config_source_priceType
 *  Default: 0
 *
 *   Can be configured on website view
 */
$this->setConfigData('tax/calculation/price_includes_tax', 1); // Including Tax

/**
 * Tax > Calculation Settings > Shipping Prices
 *  Source: tax/system_config_source_priceType
 *  Default: 0
 *
 *   Can be configured on website view
 */
$this->setConfigData('tax/calculation/shipping_includes_tax', 1); // Including Tax

/**
 * Tax > Shopping Cart Display Settings > Include Tax In Grand Total
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/cart_display/grandtotal', 1);

/**
 * Tax > Shopping Cart Display Settings > Display Prices
 *  Source: tax/system_config_source_tax_display_type
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/cart_display/price', 2); // Including Tax

/**
 * Tax > Shopping Cart Display Settings > Display Shipping Amount
 *  Source: tax/system_config_source_tax_display_type
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/cart_display/shipping', 2); // Including Tax

/**
 * Tax > Shopping Cart Display Settings > Display Subtotal
 *  Source: tax/system_config_source_tax_display_type
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/cart_display/subtotal', 2); // Including Tax

/**
 * Tax > Default Tax Destination Calculation > Default Country
 *  Source: tax/system_config_source_tax_country
 *  Default: US
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/defaults/country', 'FR');

/**
 * Tax > Default Tax Destination Calculation > Default Post Code
 *  Default: *
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/defaults/postcode', '*');

/**
 * Tax > Default Tax Destination Calculation > Default State
 *  Source: tax/system_config_source_tax_region
 *  Default: 0
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/defaults/region', '0');

/**
 * Tax > Price Display Settings > Display Shipping Prices
 *  Source: tax/system_config_source_tax_display_type
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/display/shipping', 2); // Including Tax

/**
 * Tax > Price Display Settings > Display Product Prices In Catalog
 *  Source: tax/system_config_source_tax_display_type
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/display/type', 2); // Including Tax

/**
 * Tax > Orders, Invoices, Credit Memos Display Settings > Include Tax In Grand Total
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/sales_display/grandtotal', 1);

/**
 * Tax > Orders, Invoices, Credit Memos Display Settings > Display Prices
 *  Source: tax/system_config_source_tax_display_type
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/sales_display/price', 2); // Including Tax

/**
 * Tax > Orders, Invoices, Credit Memos Display Settings > Display Shipping Amount
 *  Source: tax/system_config_source_tax_display_type
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/sales_display/shipping', 2); // Including Tax

/**
 * Tax > Orders, Invoices, Credit Memos Display Settings > Display Subtotal
 *  Source: tax/system_config_source_tax_display_type
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/sales_display/subtotal', 2); // Including Tax

/**
 * Tax > Orders, Invoices, Credit Memos Display Settings > Display Zero Tax Subtotal
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 0
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('tax/sales_display/zero_tax', 0);

/**
 * Store Email Addresses > Custom Email 1 > Sender Email
 *  Default: custom1@example.com
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('trans_email/ident_custom1/email', '');

/**
 * Store Email Addresses > Custom Email 1 > Sender Name
 *  Default: Custom 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('trans_email/ident_custom1/name', '-');

/**
 * Store Email Addresses > Custom Email 2 > Sender Email
 *  Default: custom2@example.com
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('trans_email/ident_custom2/email', '');

/**
 * Store Email Addresses > Custom Email 2 > Sender Name
 *  Default: Custom 2
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('trans_email/ident_custom2/name', '--');

/**
 * Store Email Addresses > General Contact > Sender Email
 *  Default: owner@example.com
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('trans_email/ident_general/email', 'contact@sabre.fr');

/**
 * Store Email Addresses > General Contact > Sender Name
 *  Default: Owner
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('trans_email/ident_general/name', 'Sabre');

/**
 * Store Email Addresses > Sales Representative > Sender Email
 *  Default: sales@example.com
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('trans_email/ident_sales/email', 'contact@sabre.fr');

/**
 * Store Email Addresses > Sales Representative > Sender Name
 *  Default: Sales
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('trans_email/ident_sales/name', 'Service des ventes Sabre');

/**
 * Store Email Addresses > Customer Support > Sender Email
 *  Default: support@example.com
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('trans_email/ident_support/email', 'contact@sabre.fr');

/**
 * Store Email Addresses > Customer Support > Sender Name
 *  Default: CustomerSupport
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('trans_email/ident_support/name', 'Service clients Sabre');

/**
 * Web > Session Cookie Management > Cookie Domain
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
//$this->setConfigData('web/cookie/cookie_domain', 'sabre.fr');

/**
 * Web > Session Cookie Management > Use HTTP Only
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('web/cookie/cookie_httponly', 1);

/**
 * Web > Session Cookie Management > Cookie Lifetime
 *  Default: 3600
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('web/cookie/cookie_lifetime', 0);

/**
 * Web > Session Cookie Management > Cookie Path
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('web/cookie/cookie_path', '');

/**
 * Web > Polls > Disallow Voting in a Poll Multiple Times from Same IP-address
 *  Source: adminhtml/system_config_source_yesno
 *  Default:
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('web/polls/poll_check_by_ip', 1);

/**
 * Web > Search Engines Optimization > Use Web Server Rewrites
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('web/seo/use_rewrites', 1);

/**
 * Web > Session Validation Settings > Use SID on Frontend
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on website view
 */
$this->setConfigData('web/session/use_frontend_sid', 0);

/**
 * Wishlist > General Options > Enabled
 *  Source: adminhtml/system_config_source_yesno
 *  Default: 1
 *
 *   Can be configured on store view
 *   Can be configured on website view
 */
$this->setConfigData('wishlist/general/active', 0);
