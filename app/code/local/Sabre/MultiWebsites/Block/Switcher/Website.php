<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 23/12/2016
 * Time: 12:24
 */
class Sabre_MultiWebsites_Block_Switcher_Website extends Mage_Core_Block_Template
{

    public function getWebsites() {

        return $this->helper('sabre_multiwebsites')->getOtherActiveWebsites();

    }


    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        // On affiche le bloc si c'est autorisé.
        if (Mage::getStoreConfigFlag("sabre_multiwebsites/general/show_countries")) {
            return parent::_toHtml();
        }
        return '';
    }

}