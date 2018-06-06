<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Export CSV button for shipping table rates
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Sabre_Dataflow_Block_System_Config_Form_Field_GenerateXsltParams extends Varien_Data_Form_Element_Abstract
{
    public function getElementHtml()
    {
        Mage::log(__METHOD__);
        $buttonBlock = $this->getForm()->getParent()->getLayout()->createBlock('adminhtml/widget_button');

        $data = array(
            'label'     => Mage::helper('adminhtml')->__('Generate XSLT Params'),
            'onclick'   => 'setLocation(\''.Mage::helper('adminhtml')->getUrl("*/*/generateXsltParams") . '\')',
            'class'     => '',
        );

        $html = $buttonBlock->setData($data)->toHtml();

        return $html;
    }
}
