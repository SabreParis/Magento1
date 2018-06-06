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
class Sabre_Dataflow_Model_Import_Sales_OrderStatus_Observer
{

    const SCRIPT_CODE = 'import/sales/order_status';

    /**
     * 
     * @param type $observer
     * @return \Sabre_Dataflow_Model_Import_Sales_OrderStatus_Observer
     * @throws Exception
     */
    public function getFilesToImport($observer)
    {
        try {

            /* @var $ftpTransport Sabre_Dataflow_Model_Import_Transport_Ftp */
            $ftpTransport = Mage::getModel("sabre_dataflow/import_transport_ftp");

            $sourcepath = Mage::getStoreConfig("ayaline_dataflowmanager/import_sales_orderstatus/ftp_source_path");

            if (!$sourcepath) {
                throw new Exception("FTP path is not defined in Magento");
            }

            $opened = $ftpTransport->open($sourcepath);

            if ($opened) {
                try {

                    $files2process = $ftpTransport->ls(array($this, "filterByFilename"));

                    if (count($files2process)>0) {
                        foreach ($files2process as $file2process) {
                            $destination = $this->_getDestinationFile();
                            // Copie du fichier du FTP vers le serveur Web
                            $ftpTransport->read($file2process['id'], $destination);
                            // Suppression du fichier sur le FTP
                            $ftpTransport->rm($file2process['id']);
                        }
                        try {
                            $dataflow = Mage::getModel("sabre_dataflow/import_sales_orderStatus");
                            $dataflow->execute(array('data_flow' => "import/sales/order_status"));
                        } catch (Exception $e) {
                            Mage::logException($e);
                        }
                    }

                } catch (Exception $ex) {
                    throw new Exception($ex);
                }
            } else {
                throw new Exception("FTP connexion is not opened");
            }
        } catch (Exception $e) {
            Mage::log($e->getMessage());
            Mage::logException($e);
        }

        return $this;
    }

    function filterByFilename($fileInfos) {
        $filenameModel = Mage::getStoreConfig("ayaline_dataflowmanager/import_sales_orderstatus/ftp_source_file");
        $filename = $fileInfos["text"];
        if (fnmatch($filenameModel, $filename)) {
            return true;
        }
        return false;
    }

    /**
     * 
     * @return string
     */
    protected function _getDestinationFile()
    {
        $scriptConfig = Mage::getSingleton('ayaline_dataflowmanager/config')->getScriptConfig(self::SCRIPT_CODE);
        $io = new Varien_Io_File();

        $basePath = $io->getCleanPath(Mage::getSingleton('ayaline_dataflowmanager/system_config')->getBasePath('import'));
        $importPath = trim($scriptConfig->import_directory->__toString(), DS);
        $destinationPath = $io->getCleanPath($basePath . DS . $importPath . DS . Sabre_Dataflow_Model_Import_Sales_OrderStatus::IMPORT_INPUT_FOLDER);

        $destFileNamePattern = $importPath = trim($scriptConfig->files_name_pattern->__toString(), DS);
        $destFileName = str_replace('*', date('Y-m-dTHis-' . rand(10000000,99999999)), $destFileNamePattern);

        $destination = $io->getCleanPath($destinationPath . DS . $destFileName);

        return $destination;
    }
}
