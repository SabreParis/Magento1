#!/bin/sh

############################################
# Initialisation des variables
############################################
MAGENTO_PATH="/var/www/sabre"

SOURCE_FILENAME="catalog.xml"
SOURCE_DIRECTORY="/tmp"
DESTINATION_FILENAME="products2import.xml"
DESTINATION_DIRECTORY="/var/www/sabre/var/dataflow/catalog/product/input"
ARCHIVE_DIRECTORY="/var/www/sabre/var/dataflow/catalog/product/processed"

PHPPATHEXEC="/usr/bin/php"

TIMESTAMP=$(date +%Y%m%d%H%M)

############################################
# Existence du fichier à traiter
############################################
echo "------------------------------"
echo "ETAPE 1 : Recherche du fichier à traiter"
echo "------------------------------"
SRCFILE="${SOURCE_DIRECTORY}/${SOURCE_FILENAME}"
if [ ! -f $SRCFILE ]
then
    echo "fichier non trouvé"
    exit
fi

############################################
# transformation of XML source
############################################
echo "------------------------------"
echo "ETAPE 2 : Transformation of XML source"
echo "------------------------------"
cd ${MAGENTO_PATH}
java -jar lib/saxon/saxon9he.jar -s:${SOURCE_DIRECTORY}/${SOURCE_FILENAME} -xsl:${MAGENTO_PATH}/app/code/local/Sabre/Dataflow/xslt/sabre-products.xslt -o:${DESTINATION_DIRECTORY}/${DESTINATION_FILENAME}
if [ ! -f ${DESTINATION_DIRECTORY}/${DESTINATION_FILENAME} ]
then
    echo "Transformation echouée"
    exit
fi

############################################
# Renommage du fichier source
############################################
echo "------------------------------"
echo "ETAPE 3 : Renommage du fichier source"
echo "------------------------------"
mv ${SOURCE_DIRECTORY}/${SOURCE_FILENAME} ${SOURCE_DIRECTORY}/${SOURCE_FILENAME}.${TIMESTAMP}

############################################
# Chargement
############################################
echo "------------------------------"
echo "ETAPE 4 : Chargement dans Magento"
echo "------------------------------"
#${PHPPATHEXEC} shell/dataflow.php --data_flow import/catalog/product --console --use_cache 0 --file ${DESTINATION_FILENAME}
${PHPPATHEXEC} shell/dataflow.php --data_flow import/catalog/product --file ${DESTINATION_FILENAME}
#${PHPPATHEXEC} shell/dataflow.php --data_flow import/catalog/product --use_cache 0 --file ${DESTINATION_FILENAME}

############################################
# Renommage du fichier
############################################
echo "------------------------------"
echo "ETAPE 5 : Renommage du fichier destination"
echo "------------------------------"
if [ -f $SRCFILE ]
then
    mv ${DESTINATION_DIRECTORY}/${DESTINATION_FILENAME} ${ARCHIVE_DIRECTORY}/${DESTINATION_FILENAME}.${TIMESTAMP}
fi

echo "Chargement fini !"