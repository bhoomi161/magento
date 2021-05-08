<?php

class Ccc_Vendor_Block_Account_Dashboard_Address extends Mage_Core_Block_Template
{
    public function getVendor()
    {
        return Mage::getSingleton('vendor/session')->getVendor();
    }

    public function getPrimaryShippingAddressHtml()
    {
        $address = $this->getVendor()->getPrimaryShippingAddress();

        if( $address instanceof Varien_Object ) {
            return $address->format('html');
        } else {
            return Mage::helper('vendor')->__('You have not set a default shipping address.');
        }
    }

    public function getPrimaryBillingAddressHtml()
    {
        $address = $this->getVendor()->getPrimaryBillingAddress();

        if( $address instanceof Varien_Object ) {
            return $address->format('html');
        } else {
            return Mage::helper('vendor')->__('You have not set a default billing address.');
        }
    }

    public function getPrimaryShippingAddressEditUrl()
    {
        return Mage::getUrl('vendor/address/edit', array('id'=>$this->getVendor()->getDefaultShipping()));
    }

    public function getPrimaryBillingAddressEditUrl()
    {
        return Mage::getUrl('vendor/address/edit', array('id'=>$this->getVendor()->getDefaultBilling()));
    }

    public function getAddressBookUrl()
    {
        return $this->getUrl('vendor/address/');
    }
}
