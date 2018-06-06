<?php
/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
/* @var $this Mage_Core_Model_Resource_Setup */

/* @var $grid BL_CustomGrid_Model_Grid */
$grid = Mage::getModel('customgrid/grid')->load('product', 'type');
$gridId = $grid->getGridId();


if ($grid->getMaxAttributeColumnId() > 0) {
    $columnId = $grid->getMaxAttributeColumnId() + 1;
} else {
    $columnId = 1;
}
$grid->setMaxAttributeColumnId($columnId);

$newColumnId = BL_CustomGrid_Model_Grid::GRID_COLUMN_ATTRIBUTE_ID_PREFIX . $columnId;

$gridNextOrder = $grid->getMaxOrder();

$gridNextOrder += $grid->getOrderPitch();


$columnData = array();

$columnsData['a_article'] = array(
    'grid_id' => $gridId,
    'id' => $newColumnId,
    'index' => 'a_article',
    'width' => '',
    'align' => BL_CustomGrid_Model_Grid::GRID_COLUMN_ALIGNMENT_LEFT,
    'header' => 'Type article',
    'order' => $gridNextOrder,
    'origin' => BL_CustomGrid_Model_Grid::GRID_COLUMN_ORIGIN_ATTRIBUTE,
    'is_visible' => 1,
    'filter_only' => 0,
    'is_system' => 0,
    'missing' => 0,
    'store_id' => null,
    'renderer_type' => null,
    'renderer_params' => null,
    'allow_edit' => 1,
    'custom_params' => null,
);
$newColumnId++;
$gridNextOrder += $grid->getOrderPitch();

$columnsData['a_model'] = array(
    'grid_id' => $gridId,
    'id' => $newColumnId,
    'index' => 'a_model',
    'width' => '',
    'align' => BL_CustomGrid_Model_Grid::GRID_COLUMN_ALIGNMENT_LEFT,
    'header' => 'Modele',
    'order' => $gridNextOrder,
    'origin' => BL_CustomGrid_Model_Grid::GRID_COLUMN_ORIGIN_ATTRIBUTE,
    'is_visible' => 1,
    'filter_only' => 0,
    'is_system' => 0,
    'missing' => 0,
    'store_id' => null,
    'renderer_type' => null,
    'renderer_params' => null,
    'allow_edit' => 1,
    'custom_params' => null,
);
$newColumnId++;
$gridNextOrder += $grid->getOrderPitch();

$columnsData['color'] = array(
    'grid_id' => $gridId,
    'id' => $newColumnId,
    'index' => 'color',
    'width' => '',
    'align' => BL_CustomGrid_Model_Grid::GRID_COLUMN_ALIGNMENT_LEFT,
    'header' => 'Couleur',
    'order' => $gridNextOrder,
    'origin' => BL_CustomGrid_Model_Grid::GRID_COLUMN_ORIGIN_ATTRIBUTE,
    'is_visible' => 1,
    'filter_only' => 0,
    'is_system' => 0,
    'missing' => 0,
    'store_id' => null,
    'renderer_type' => null,
    'renderer_params' => null,
    'allow_edit' => 1,
    'custom_params' => null,
);
$newColumnId++;
$gridNextOrder += $grid->getOrderPitch();

foreach ($columnsData as $colData) {
    $grid->addColumn($colData['id'], $colData);    
}

$grid->save();