<?php

/**
 * Created : 2015
 *
 * @category Ayaline
 * @package Ayaline_XXXX
 * @author aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Sabre_Dataflow_Model_Import_Catalog_Observer
{

    public function getCatalogFilesToImport($observer)
    {
        try {

            Mage::log(">>> ================================ >>>");
            Mage::log(__METHOD__);

            /* @var $ftpTransport Sabre_Dataflow_Model_Import_Transport_Ftp */
            $ftpTransport = Mage::getModel("sabre_dataflow/import_transport_ftp");

            $sourcepath = Mage::getStoreConfig("ayaline_dataflowmanager/import_product/ftp_source_path");
            $filename = Mage::getStoreConfig("ayaline_dataflowmanager/import_product/ftp_source_file");
            if (!$sourcepath || !$filename) {
                throw new Exception("FTP path is not defined in Magento");
            }

            $importBasePath = Mage::getStoreConfig("ayaline_dataflowmanager/import_product/ftp_destination_directory");
            $destinationDir = $ftpTransport->getIo()->getCleanPath($importBasePath);
            $destination = $destinationDir . $filename;

            Mage::log("source directory : $sourcepath");
            Mage::log("destination : $destination");
            Mage::log("file name : $filename");

            $opened = $ftpTransport->open($sourcepath);

            if ($opened) {
                $result = $ftpTransport->read($filename, $destination);
                if (!$result) {
                    throw new Mage_Exception("File was not copied");
                }

            } else {
                throw new Mage_Exception("FTP connexion is not opened");
            }

            Mage::log("$filename copied from FTP to Local directory");

        } catch (Mage_Exception $me) {
            Mage::logException($me);
        }

        Mage::log("<<< ================================ <<<");

        return $this;
    }

    public function importInventory() {
        $this->_importDatas("import_inventory", "import/catalog/inventory", "sabre_dataflow/import_catalog_inventory");
    }

    public function importPricing() {
        $this->_importDatas("import_price", "import/catalog/pricing", "sabre_dataflow/import_catalog_pricing");
    }

    public function importShops() {
        $this->_importDatas("import_shops", "import/catalog/shops", "sabre_dataflow/import_catalog_shops");
    }

    protected function _importDatas($systemConfigGroup, $scriptName, $importModel) {

        // Récupération du fichier
        // Si fichier, appel de l'import

        try {

            /* @var $ftpTransport Sabre_Dataflow_Model_Import_Transport_Ftp */
            $ftpTransport = Mage::getModel("sabre_dataflow/import_transport_ftp");

            $sourcepath = Mage::getStoreConfig("ayaline_dataflowmanager/$systemConfigGroup/ftp_source_path");
            $filename = Mage::getStoreConfig("ayaline_dataflowmanager/$systemConfigGroup/ftp_source_file");
            if (!$sourcepath || !$filename) {
                throw new Exception("FTP path is not defined in Magento");
            }

            $basePath = Mage::getSingleton('ayaline_dataflowmanager/system_config')->getBasePath('import');
            $dataflowConfig = Mage::getSingleton('ayaline_dataflowmanager/config')->getScriptConfig($scriptName);
            $importPath = trim($dataflowConfig->import_directory->__toString(), DS);
            $inputFolder = $basePath . DS . $importPath . DS . "input";
            $destination = $inputFolder . DS . trim($dataflowConfig->files_name_pattern->__toString());
            $destination = str_replace("//", "/", $destination);

            Mage::log("source directory : $sourcepath");
            Mage::log("destination : $destination");
            Mage::log("file name : $filename");

            $opened = $ftpTransport->open($sourcepath);

            if ($opened) {
                $result = $ftpTransport->read($filename, $destination);
                if (!$result) {
                    throw new Mage_Exception("File was not copied");
                }

            } else {
                throw new Mage_Exception("FTP connexion is not opened");
            }

            Mage::log("$filename copied from FTP to Local directory");

        } catch (Mage_Exception $me) {
            Mage::logException($me);
        }

        try {

            Mage::log("Begin import");

            $dataflow = Mage::getModel($importModel);
            $dataflow->execute(array('data_flow' => $scriptName));

            Mage::log("import finished");

        } catch (Exception $e) {
            Mage::logException($e);
        }

    }

}
