<?php

/**
 * created: 2016
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */


// insertion des groupes de magasin (boutique / revendeur)
$names = array("boutique", "revendeur");
foreach ($names as $name) {
    $shopGroup = Mage::getModel("ayalineshop/shop_group");
    $shopGroup->setData("name", $name);
    $shopGroup->save();
}

