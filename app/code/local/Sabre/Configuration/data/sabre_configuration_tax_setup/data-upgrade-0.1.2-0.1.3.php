<?php
/**
 * created : 2015
 *
 * @category Ayaline
 * @package Ayaline_Configuration
 * @author alay
 * @copyright Ayaline - 2015 - http://www.ayaline.com
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Tax_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
	TRUNCATE TABLE {$this->getTable('tax/tax_class')};
	TRUNCATE TABLE {$this->getTable('tax/tax_calculation')};
	TRUNCATE TABLE {$this->getTable('tax/tax_calculation_rule')};
	TRUNCATE TABLE {$this->getTable('tax/tax_calculation_rate')};
");

$data = array(
    array(
        'class_id' => 1,
        'class_name' => 'TVA Normale',
        'class_type' => Mage_Tax_Model_Class::TAX_CLASS_TYPE_PRODUCT
    ),
    array(
        'class_id' => 2,
        'class_name' => 'TVA Livraison',
        'class_type' => Mage_Tax_Model_Class::TAX_CLASS_TYPE_PRODUCT
    ),
    array(
        'class_id' => 3,
        'class_name' => 'TVA Client',
        'class_type' => Mage_Tax_Model_Class::TAX_CLASS_TYPE_CUSTOMER
    ),
);

foreach ($data as $row) {
    $installer->getConnection()->insertForce($installer->getTable('tax/tax_class'), $row);
}

/**
 * install tax calculation rule
 */

$data = array(
    array(
        'tax_calculation_rule_id' => 1,
        'code' => 'Regle TVA Normale',
        'priority' => 1,
        'position' => 1
    ),
        array(
            'tax_calculation_rule_id' => 2,
            'code' => 'Regle TVA Zero',
            'priority' => 0,
            'position' => 0
        )
);

foreach ($data as $row) {
    $installer->getConnection()->insertForce($installer->getTable('tax/tax_calculation_rule'), $row);
}

/**
 * install tax calculation rates
 */


$dataNormalRate = array(
    array(
        'tax_country_id' => 'GB',
        'tax_region_id' => 0,
        'tax_postcode' => '*',
        'code' => '20 - GRANDE BRETAGNE',
        'rate' => '20'
    ),
    array(
        'tax_country_id' => 'ES',
        'tax_region_id' => 0,
        'tax_postcode' => '*',
        'code' => '20 - ESPAGNE',
        'rate' => '20'
    ),
    array(
        'tax_country_id' => 'IT',
        'tax_region_id' => 0,
        'tax_postcode' => '*',
        'code' => '20 - ITALIE',
        'rate' => '20'
    ),
    array(
        'tax_country_id' => 'IE',
        'tax_region_id' => 0,
        'tax_postcode' => '*',
        'code' => '20 - IRLANDE',
        'rate' => '20'
    ),
    array(
        'tax_country_id' => 'CH',
        'tax_region_id' => 0,
        'tax_postcode' => '*',
        'code' => '0 - SUISSE',
        'rate' => '0'
    ),
    array(
        'tax_country_id' => 'GR',
        'tax_region_id' => 0,
        'tax_postcode' => '*',
        'code' => '20 - GRECE',
        'rate' => '20'
    ),
    array(
        'tax_country_id' => 'FR',
        'tax_region_id' => 0,
        'tax_postcode' => '*',
        'code' => '20 - FRANCE',
        'rate' => '20'
    )

);


$i = 0;
$normalRateIds = array();
$freeRateIds = array();
foreach ($dataNormalRate as $row) {
    $i++;
    $row['tax_calculation_rate_id'] = $i;
    if($row['tax_country_id'] == 'CH'){
        $freeRateIds[] = $i;;
    }else{
        $normalRateIds[] = $i;
    }
    $installer->getConnection()->insertForce($installer->getTable('tax/tax_calculation_rate'), $row);
}

//tax_calculation

$data = array(
    array(
        'tax_calculation_rule_id' => 1,
        'customer_tax_class_id' => 3,
        'product_tax_class_id' => 1
    ),
    array(
        'tax_calculation_rule_id' => 1,
        'customer_tax_class_id' => 3,
        'product_tax_class_id' => 2
    )
);
foreach ($normalRateIds as $i) {
    foreach ($data as $row) {
        $row['tax_calculation_rate_id'] = $i;
        $installer->getConnection()->insertForce($installer->getTable('tax/tax_calculation'), $row);
    }
}
$data = array(
    array(
        'tax_calculation_rule_id' => 2,
        'customer_tax_class_id' => 3,
        'product_tax_class_id' => 1
    ),
);

foreach ($freeRateIds as $i) {
    foreach ($data as $row) {
        $row['tax_calculation_rate_id'] = $i;
        $installer->getConnection()->insertForce($installer->getTable('tax/tax_calculation'), $row);
    }
}


/***************************************************************************************
 *                                        CONFIGURATION
 ***************************************************************************************/

//------> Ventes > TVA & Autres Taxes
//Classes de taxe
$installer->setConfigData('tax/classes/shipping_tax_class', 2);

//Paramètres de calcul
$installer->setConfigData('tax/calculation/based_on', 'billing');
$installer->setConfigData('tax/calculation/price_includes_tax', 1);
$installer->setConfigData('tax/calculation/shipping_includes_tax', 1);
$installer->setConfigData('tax/calculation/apply_after_discount', 1);

//Estimation du calcul de la taxe par défaut
$installer->setConfigData('tax/defaults/country', 'FR');

//Paramètre d'affichage des prix
$installer->setConfigData('tax/display/type', 2);
$installer->setConfigData('tax/display/shipping', 2);

//Afficher les options du panier
$installer->setConfigData('tax/cart_display/price', 2);
$installer->setConfigData('tax/cart_display/shipping', 2);
$installer->setConfigData('tax/cart_display/subtotal', 2);
$installer->setConfigData('tax/cart_display/grandtotal', 1);
$installer->setConfigData('tax/cart_display/zero_tax', 1);

//Afficher les options du détail de la commande/facture/etc.
$installer->setConfigData('tax/sales_display/price', 3);
$installer->setConfigData('tax/sales_display/shipping', 3);
$installer->setConfigData('tax/sales_display/subtotal', 3);
$installer->setConfigData('tax/sales_display/grandtotal', 1);
$installer->setConfigData('tax/sales_display/zero_tax', 1);

//WEEE
$installer->setConfigData('tax/weee/enable', 0);