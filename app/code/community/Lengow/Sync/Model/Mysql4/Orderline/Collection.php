<?php

/**
 * Lengow sync model mysql4 orderline collection
 *
 * @category    Lengow
 * @package     Lengow_Sync
 * @author      Team Connector <team-connector@lengow.com>
 * @copyright   2016 Lengow SAS
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Lengow_Sync_Model_Mysql4_Orderline_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('lensync/orderline');
    }
}
