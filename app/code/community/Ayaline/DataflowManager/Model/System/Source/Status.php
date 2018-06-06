<?php

/**
 * created: 2014
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2014 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_DataflowManager_Model_System_Source_Status
{

    const STATUS_NOT_RUNNING = 'not_running';
    const STATUS_RUNNING = 'running';

    public function getOptions()
    {
        return array(
            self::STATUS_NOT_RUNNING => Mage::helper('ayaline_dataflowmanager')->__('Not running'),
            self::STATUS_RUNNING     => Mage::helper('ayaline_dataflowmanager')->__('Running'),
        );
    }

} 