<?php

class Ccc_Vendor_Block_Widget_Gender extends Ccc_Vendor_Block_Widget_Abstract
{
    /**
     * Initialize block
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('vendor/widget/gender.phtml');
    }

    /**
     * Check if gender attribute enabled in system
     *
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->_getAttribute('gender')->getIsVisible();
    }

    /**
     * Check if gender attribute marked as required
     *
     * @return bool
     */
    public function isRequired()
    {
        return (bool)$this->_getAttribute('gender')->getIsRequired();
    }

   
    public function getVendor()
    {
        return Mage::getSingleton('vendor/session')->getVendor();
    }
}
