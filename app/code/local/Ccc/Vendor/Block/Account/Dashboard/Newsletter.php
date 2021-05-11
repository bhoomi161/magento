<?php

class Ccc_Vendor_Block_Account_Dashboard_Newsletter extends Mage_Core_Block_Template
{
    public function getSubscriptionObject()
    {
        // if(is_null($this->_subscription)) {
        //     $this->_subscription = Mage::getModel('newsletter/subscriber')
        //         ->loadByVendor(Mage::getSingleton('vendor/session')->getVendor());
        // }
        // return $this->_subscription;
    }
}
