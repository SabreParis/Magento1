<?php

class Sabre_CatalogSearch_Block_Layer_Filter_Attribute extends Sabre_Catalog_Block_Layer_Filter_Attribute
{
    /**
     * Set filter model name
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 'catalogsearch/layer_filter_attribute';
    }
}
