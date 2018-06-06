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

/** @var $this Mage_Catalog_Model_Resource_Setup */

$this->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_model');
$this->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_article');
$this->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_color');
$this->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_is_set');

// use Color as real color and a_filter_color as color for layered navigation
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'color', 'note', 'couleur réelle du produit - visible sur la fiche');


$position = 500;

$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_is_set', array(
    'label'                      => 'Est un set',
    'input'                      => 'select',
    'type'                       => 'int',
    'backend'                    => null,
    'source'                     => 'eav/entity_attribute_source_boolean',
    'table'                      => null,
    'frontend'                   => null,
    'frontend_class'             => null,
    'required'                   => 1,
    'user_defined'               => 0,
    'default'                    => 0,
    'unique'                     => 0,
    'note'                       => '',
    'visible'                    => 1,
    'searchable'                 => 0,
    'filterable'                 => 0,
    'comparable'                 => 0,
    'visible_on_front'           => 0,
    'wysiwyg_enabled'            => 0,
    'is_html_allowed_on_front'   => 0,
    'visible_in_advanced_search' => 0,
    'filterable_in_search'       => 0,
    'used_in_product_listing'    => 0,
    'used_for_sort_by'           => 0,
    'apply_to'                   => null,
    'position'                   => ++$position,
    'sort_order'                 => $position,
    'is_configurable'            => 0,
    'group'                      => 'General',
    'used_for_promo_rules'       => 0,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_article', array(
    'label'                      => 'Article',
    'input'                      => 'select',
    'type'                       => 'int',
    'backend'                    => null,
    'source'                     => null,
    'table'                      => null,
    'frontend'                   => null,
    'frontend_class'             => null,
    'required'                   => 1,
    'user_defined'               => 0,
    'default'                    => null,
    'unique'                     => 0,
    'note'                       => "type d'article",
    'visible'                    => 1,
    'searchable'                 => 0,
    'filterable'                 => 0,
    'comparable'                 => 0,
    'visible_on_front'           => 0,
    'wysiwyg_enabled'            => 0,
    'is_html_allowed_on_front'   => 0,
    'visible_in_advanced_search' => 0,
    'filterable_in_search'       => 0,
    'used_in_product_listing'    => 0,
    'used_for_sort_by'           => 0,
    'apply_to'                   => null,
    'position'                   => ++$position,
    'sort_order'                 => $position,
    'is_configurable'            => 0,
    'group'                      => 'General',
    'used_for_promo_rules'       => 0,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_model', array(
    'label'                      => 'Modèle',
    'input'                      => 'select',
    'type'                       => 'int',
    'backend'                    => null,
    'source'                     => null,
    'table'                      => null,
    'frontend'                   => null,
    'frontend_class'             => null,
    'required'                   => 1,
    'user_defined'               => 0,
    'default'                    => null,
    'unique'                     => 0,
    'note'                       => '',
    'visible'                    => 1,
    'searchable'                 => 0,
    'filterable'                 => 0,
    'comparable'                 => 0,
    'visible_on_front'           => 0,
    'wysiwyg_enabled'            => 0,
    'is_html_allowed_on_front'   => 0,
    'visible_in_advanced_search' => 0,
    'filterable_in_search'       => 0,
    'used_in_product_listing'    => 0,
    'used_for_sort_by'           => 0,
    'apply_to'                   => null,
    'position'                   => ++$position,
    'sort_order'                 => $position,
    'is_configurable'            => 0,
    'group'                      => 'General',
    'used_for_promo_rules'       => 0,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'a_filter_color', array(
    'label'                      => 'Couleur',
    'input'                      => 'select',
    'type'                       => 'int',
    'backend'                    => null,
    'source'                     => null,
    'table'                      => null,
    'frontend'                   => null,
    'frontend_class'             => null,
    'required'                   => 1,
    'user_defined'               => 0,
    'default'                    => null,
    'unique'                     => 0,
    'note'                       => 'couleur utilisée pour les filtres',
    'visible'                    => 1,
    'searchable'                 => 0,
    'filterable'                 => 0,
    'comparable'                 => 0,
    'visible_on_front'           => 0,
    'wysiwyg_enabled'            => 0,
    'is_html_allowed_on_front'   => 0,
    'visible_in_advanced_search' => 0,
    'filterable_in_search'       => 0,
    'used_in_product_listing'    => 0,
    'used_for_sort_by'           => 0,
    'apply_to'                   => null,
    'position'                   => ++$position,
    'sort_order'                 => $position,
    'is_configurable'            => 0,
    'group'                      => 'General',
    'used_for_promo_rules'       => 0,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));


$frenchStoreId = Mage::getModel('core/store')->load('sabre_fr_french', 'code')->getId();
$englishStoreId = Mage::getModel('core/store')->load('sabre_fr_english', 'code')->getId();

// create all a_article option value
$aArticleOptions = [
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'set-4-couverts',
        $frenchStoreId                      => 'Set 4 Couverts',
        $englishStoreId                     => 'Set 4 Couverts',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couteau-a-dessert',
        $frenchStoreId                      => 'Couteau à dessert',
        $englishStoreId                     => 'Couteau à dessert',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'pelle-a-tarte-coupante',
        $frenchStoreId                      => 'Pelle à tarte coupante',
        $englishStoreId                     => 'Pelle à tarte coupante',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'decapsuleur',
        $frenchStoreId                      => 'Décapsuleur',
        $englishStoreId                     => 'Décapsuleur',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'cuillere-de-table',
        $frenchStoreId                      => 'Cuillère de table',
        $englishStoreId                     => 'Cuillère de table',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'fourchette-a-gateau',
        $frenchStoreId                      => 'Fourchette à gâteau',
        $englishStoreId                     => 'Fourchette à gâteau',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couteau-a-pain',
        $frenchStoreId                      => 'Couteau à pain',
        $englishStoreId                     => 'Couteau à pain',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couteau-a-tomate',
        $frenchStoreId                      => 'Couteau à tomate',
        $englishStoreId                     => 'Couteau à tomate',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'fourchette-de-table',
        $frenchStoreId                      => 'Fourchette de table',
        $englishStoreId                     => 'Fourchette de table',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couteau-a-fruit',
        $frenchStoreId                      => 'Couteau à fruit',
        $englishStoreId                     => 'Couteau à fruit',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couteau-a-beurre',
        $frenchStoreId                      => 'Couteau à beurre',
        $englishStoreId                     => 'Couteau à beurre',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'eplucheur',
        $frenchStoreId                      => 'Éplucheur',
        $englishStoreId                     => 'Éplucheur',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couteau-de-table',
        $frenchStoreId                      => 'Couteau de table',
        $englishStoreId                     => 'Couteau de table',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'fourchette-a-huitre',
        $frenchStoreId                      => 'Fourchette à huitre',
        $englishStoreId                     => 'Fourchette à huitre',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'tartineur',
        $frenchStoreId                      => 'Tartineur',
        $englishStoreId                     => 'Tartineur',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couteau-d-office',
        $frenchStoreId                      => 'Couteau d’office',
        $englishStoreId                     => 'Couteau d’office',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'cuillere-a-cafe',
        $frenchStoreId                      => 'Cuillère à café',
        $englishStoreId                     => 'Cuillère à café',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couvert-a-poisson',
        $frenchStoreId                      => 'Couvert à poisson',
        $englishStoreId                     => 'Couvert à poisson',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couteau-a-fromage',
        $frenchStoreId                      => 'Couteau à fromage',
        $englishStoreId                     => 'Couteau à fromage',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'louche',
        $frenchStoreId                      => 'Louche',
        $englishStoreId                     => 'Louche',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couteau-de-table-lame-crantee',
        $frenchStoreId                      => 'Couteau de table lame crantée',
        $englishStoreId                     => 'Couteau de table lame crantée',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'service-a-poisson',
        $frenchStoreId                      => 'Service à poisson',
        $englishStoreId                     => 'Service à poisson',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couteau-a-fromage-pm',
        $frenchStoreId                      => 'Couteau à fromage PM',
        $englishStoreId                     => 'Couteau à fromage PM',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'service-a-gibier',
        $frenchStoreId                      => 'Service à gibier',
        $englishStoreId                     => 'Service à gibier',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couteau-a-steak',
        $frenchStoreId                      => 'Couteau à steak',
        $englishStoreId                     => 'Couteau à steak',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'service-a-servir',
        $frenchStoreId                      => 'Service à servir',
        $englishStoreId                     => 'Service à servir',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'rape-a-fromage',
        $frenchStoreId                      => 'Râpe à fromage',
        $englishStoreId                     => 'Râpe à fromage',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'cuillere-a-dessert',
        $frenchStoreId                      => 'Cuillère à dessert',
        $englishStoreId                     => 'Cuillère à dessert',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'service-salade',
        $frenchStoreId                      => 'Service salade',
        $englishStoreId                     => 'Service salade',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'couperet-a-fromage',
        $frenchStoreId                      => 'Couperet à fromage',
        $englishStoreId                     => 'Couperet à fromage',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'cuillere-a-sauce',
        $frenchStoreId                      => 'Cuillère à sauce',
        $englishStoreId                     => 'Cuillère à sauce',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'fourchette-a-dessert',
        $frenchStoreId                      => 'Fourchette à dessert',
        $englishStoreId                     => 'Fourchette à dessert',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'pelle-a-tarte',
        $frenchStoreId                      => 'Pelle à tarte',
        $englishStoreId                     => 'Pelle à tarte',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'fourchette-a-cocktail',
        $frenchStoreId                      => 'Fourchette à cocktail',
        $englishStoreId                     => 'Fourchette à cocktail',
    ],
];

$aArticleAttributeId = $this->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'a_article');
foreach ($aArticleOptions as $_aArticleOption) {
    $this->addAttributeOption(['attribute_id' => $aArticleAttributeId, 'value' => [$_aArticleOption]]);
}


// create all a_model option
$aModelOptions = [
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'aquarelle',
        $frenchStoreId                      => 'Aquarelle',
        $englishStoreId                     => 'Aquarelle',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'hibiscus',
        $frenchStoreId                      => 'Hibiscus',
        $englishStoreId                     => 'Hibiscus',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'pois',
        $frenchStoreId                      => 'Pois',
        $englishStoreId                     => 'Pois',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'transat',
        $frenchStoreId                      => 'Transat',
        $englishStoreId                     => 'Transat',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'baguette',
        $frenchStoreId                      => 'Baguette',
        $englishStoreId                     => 'Baguette',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'katmandou',
        $frenchStoreId                      => 'Katmandou',
        $englishStoreId                     => 'Katmandou',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'tulipe',
        $frenchStoreId                      => 'Tulipe',
        $englishStoreId                     => 'Tulipe',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bambou',
        $frenchStoreId                      => 'Bambou',
        $englishStoreId                     => 'Bambou',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'leon',
        $frenchStoreId                      => 'léon',
        $englishStoreId                     => 'léon',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'natura',
        $frenchStoreId                      => 'Natura',
        $englishStoreId                     => 'Natura',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'vichy',
        $frenchStoreId                      => 'Vichy',
        $englishStoreId                     => 'Vichy',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'basic',
        $frenchStoreId                      => 'Basic',
        $englishStoreId                     => 'Basic',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'leopard',
        $frenchStoreId                      => 'Léopard',
        $englishStoreId                     => 'Léopard',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'nature',
        $frenchStoreId                      => 'Nature',
        $englishStoreId                     => 'Nature',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'vintage',
        $frenchStoreId                      => 'Vintage',
        $englishStoreId                     => 'Vintage',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'breton',
        $frenchStoreId                      => 'Breton',
        $englishStoreId                     => 'Breton',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'marguerite',
        $frenchStoreId                      => 'Marguerite',
        $englishStoreId                     => 'Marguerite',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'pure',
        $frenchStoreId                      => 'Pure',
        $englishStoreId                     => 'Pure',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'zebre',
        $frenchStoreId                      => 'Zébre',
        $englishStoreId                     => 'Zébre',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'djembe',
        $frenchStoreId                      => 'Djembé',
        $englishStoreId                     => 'Djembé',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'monaco',
        $frenchStoreId                      => 'Monaco',
        $englishStoreId                     => 'Monaco',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'tartan',
        $frenchStoreId                      => 'Tartan',
        $englishStoreId                     => 'Tartan',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'dumbo',
        $frenchStoreId                      => 'Dumbo',
        $englishStoreId                     => 'Dumbo',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'paquerette',
        $frenchStoreId                      => 'Pâquerette',
        $englishStoreId                     => 'Pâquerette',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'tiare',
        $frenchStoreId                      => 'Tiaré',
        $englishStoreId                     => 'Tiaré',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'gustave',
        $frenchStoreId                      => 'Gustave',
        $englishStoreId                     => 'Gustave',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'paris',
        $frenchStoreId                      => 'Paris',
        $englishStoreId                     => 'Paris',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'tortue',
        $frenchStoreId                      => 'Tortue',
        $englishStoreId                     => 'Tortue',
    ],
];

$aModelAttributeId = $this->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'a_model');
foreach ($aModelOptions as $_aModelOption) {

    $this->addAttributeOption(['attribute_id' => $aModelAttributeId, 'value' => [$_aModelOption]]);
}


// create all a_model option
$aFilterColorOptions = [
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'marron',
        $frenchStoreId                      => 'Marron',
        $englishStoreId                     => 'Marron',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'rouge',
        $frenchStoreId                      => 'Rouge',
        $englishStoreId                     => 'Rouge',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'orange',
        $frenchStoreId                      => 'Orange',
        $englishStoreId                     => 'Orange',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'jaune',
        $frenchStoreId                      => 'Jaune',
        $englishStoreId                     => 'Jaune',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'vert',
        $frenchStoreId                      => 'Vert',
        $englishStoreId                     => 'Vert',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bleu',
        $frenchStoreId                      => 'Bleu',
        $englishStoreId                     => 'Bleu',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'rose',
        $frenchStoreId                      => 'Rose',
        $englishStoreId                     => 'Rose',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'gris',
        $frenchStoreId                      => 'Gris',
        $englishStoreId                     => 'Gris',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bois1',
        $frenchStoreId                      => 'Bois1',
        $englishStoreId                     => 'Bois1',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bois2',
        $frenchStoreId                      => 'Bois2',
        $englishStoreId                     => 'Bois2',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bois3',
        $frenchStoreId                      => 'Bois3',
        $englishStoreId                     => 'Bois3',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bois4',
        $frenchStoreId                      => 'Bois4',
        $englishStoreId                     => 'Bois4',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bois5',
        $frenchStoreId                      => 'Bois5',
        $englishStoreId                     => 'Bois5',
    ],
    [
        Mage_Core_Model_App::ADMIN_STORE_ID => 'bois6',
        $frenchStoreId                      => 'Bois6',
        $englishStoreId                     => 'Bois6',
    ],
];

$aFilterColorAttributeId = $this->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'a_filter_color');
foreach ($aFilterColorOptions as $_aFilterColorOption) {
    $this->addAttributeOption(['attribute_id' => $aFilterColorAttributeId, 'value' => [$_aFilterColorOption]]);
}

