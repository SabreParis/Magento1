<?php
class Tft_Tftorders_Model_Reftype
{
  public function toOptionArray()
  {
    return array(
      array('value' => 'id', 'label' =>'Product ID'),
      array('value' => 'sku', 'label' => 'Product SKU'),
    );
  }
}
