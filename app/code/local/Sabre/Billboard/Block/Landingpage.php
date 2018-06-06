<?php


class Sabre_Billboard_Block_Landingpage extends Mage_Core_Block_Template
{
    protected $_range = [];

    protected function _prepareLayout()
    {
        /** @var Mage_Page_Block_Html $root */
        $root = $this->getLayout()->getBlock('root');
        if ($root) {
            $root->addBodyClass('billboard-' . $this->getCurrentLandingPage()->getIdentifier());
        }

        /** @var Mage_Page_Block_Html_Head $head */
        $head = $this->getLayout()->getBlock('head');
        if ($head) {
            $head->setTitle($this->getCurrentLandingPage()->getTitle());
        }

        return parent::_prepareLayout();
    }

    public function getNextLandingPageUrl()
    {
        if ($this->getRange()['next']) {
            return $this->getPagerUrl($this->getRange()['next']);
        }

        return false;
    }

    public function getPreviousLandingPageUrl()
    {
        if ($this->getRange()['previous']) {
            return $this->getPagerUrl($this->getRange()['previous']);
        }

        return false;
    }

    public function getPagerUrl($id)
    {
        $urlParams = [];
        $urlParams['_current'] = true;
        $urlParams['_escape'] = true;
        $urlParams['_use_rewrite'] = true;
        $urlParams['_direct'] = $id;

        return $this->getUrl('*/*/*', $urlParams);
    }

    public function getAllLandingPage()
    {
        $collection = Mage::getResourceModel('ayalinebillboard/billboard_collection')
                          ->addBillboardTypeIdentifierFilter(Sabre_Billboard_Model_Billboard::LANDING_PAGE_IDENTIFIER)
                          ->addStatusFilter()
                          ->addStoreFilter(Mage::app()->getStore()->getId());

        return $collection;
    }


    public function getRange()
    {
        if (!count($this->_range)) {
            $id = $this->getCurrentLandingPage()->getId();

            $next = $this->getAllLandingPage()
                         ->addFieldToFilter('main_table.billboard_id', ['lt' => $id])
                         ->setOrder('main_table.widget_position', 'asc')
                         ->setPageSize(1)
                         ->getFirstItem()
                         ->getIdentifier();

            $previous = $this->getAllLandingPage()
                             ->addFieldToFilter('main_table.billboard_id', ['gt' => $id])
                             ->setOrder('main_table.widget_position', 'asc')
                             ->setPageSize(1)
                             ->getFirstItem()
                             ->getIdentifier();

            $this->_range = ['previous' => $previous, 'next' => $next];
        }

        return $this->_range;
    }

    /**
     * @return Sabre_Billboard_Model_Billboard
     */
    public function getCurrentLandingPage()
    {
        return Mage::registry('current_landing_page');
    }

    public function getAdditionalContent()
    {
        $processor = Mage::helper('ayalinebillboard')->getBillboardTemplateProcessor();

        return $processor->filter($this->getCurrentLandingPage()->getAdditionalContent());
    }

}