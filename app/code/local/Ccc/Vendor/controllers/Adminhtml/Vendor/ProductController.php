<?php

class Ccc_Vendor_Adminhtml_Vendor_ProductController extends Mage_Adminhtml_Controller_Action{
    public function _initAction()
    {
        $this->loadLayout()
        ->_setActiveMenu('vendor')
        ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Products'),Mage::helper('adminhtml')->__('Manage Products'));
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->_setActiveMenu('vendor');
        $this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_vendor_product'));
        $this->renderLayout();
    }
}
?>