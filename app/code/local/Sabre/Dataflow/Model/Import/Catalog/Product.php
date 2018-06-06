<?php

/**
 * Created by PhpStorm.
 * User: Laurent B
 * Date: 29/02/2016
 * Time: 13:35
 */
class Sabre_Dataflow_Model_Import_Catalog_Product extends Ayaline_DataflowManager_Model_Import_Catalog_Product
{

    protected function _import($filename)
    {

        $xmlReader = new XMLReader();
        $xmlReader->open($filename);

        // move to the first <product /> node
        while ($xmlReader->read() && $xmlReader->name !== 'product');
        // Process products
        while ($xmlReader->name === 'product') {
            $this->processProduct($xmlReader->readOuterXml());
            $xmlReader->next('product');
        }

        $xmlReader->close();

        $this->_cleanCache();
    }

}