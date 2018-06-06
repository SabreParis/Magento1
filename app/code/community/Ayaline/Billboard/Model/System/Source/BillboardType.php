<?php
/**
 * created : 10/08/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 *
 * @package Ayaline_Billboard
 */
class Ayaline_Billboard_Model_System_Source_BillboardType
{

    public function toOptionArray()
    {
        $types = Mage::getResourceModel('ayalinebillboard/billboard_type_collection')->addOrder('title', 'ASC')->getData();
        $returns = array();
        foreach ($types as $_type) {
            $returns[] = array(
                'value' => $_type['type_id'],
                'label' => $_type['title'],
            );
        }

        return $returns;
    }

}