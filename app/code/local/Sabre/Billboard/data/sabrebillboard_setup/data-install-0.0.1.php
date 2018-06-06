<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 07/10/15
 * Time: 11:08 ص
 */

/** @var $this Mage_Core_Model_Resource_Setup */

$billboardType = Mage::getModel('ayalinebillboard/billboard_type')
    ->load(Sabre_Billboard_Model_Billboard::LANDING_PAGE_IDENTIFIER, 'identifier');
if (!$billboardType->getId()) {
    $billboardType
        ->setData(array('identifier' => Sabre_Billboard_Model_Billboard::LANDING_PAGE_IDENTIFIER,
            'title' => 'Landing page'))
        ->save();
}
