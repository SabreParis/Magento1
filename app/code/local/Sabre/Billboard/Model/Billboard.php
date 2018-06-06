<?php

/**
 * Class Sabre_Billboard_Model_Billboard
 *
 * @method string getAdditionalContent()
 * @method $this setAdditionalContent(string $content)
 */
class Sabre_Billboard_Model_Billboard extends Ayaline_Billboard_Model_Billboard
{

    const LANDING_PAGE_IDENTIFIER = 'land_1';

    protected $_landingPageTypeId = null;

    protected function _getLandingPageTypeId()
    {
        if ($this->_landingPageTypeId === null) {
            $this->_landingPageTypeId = Mage::getModel('ayalinebillboard/billboard_type')
                ->load(self::LANDING_PAGE_IDENTIFIER, 'identifier')
                ->getId();
        }

        return $this->_landingPageTypeId;
    }
    public function isLandingPage()
    {
        return in_array($this->_getLandingPageTypeId(), $this->getData('type_id'));
    }
}
