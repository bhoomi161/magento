<?php
 
class Ccc_Vendor_Block_Marketplace_Product_Edit_Form extends Mage_Core_Block_Template{
    public function __construct()
    {
       $this->setTemplate('vendor/marketplace/product/edit/form.phtml');
    }
    public function getSaveUrl()
    {
       return $this->getUrl('*/product/save',array('product_id'=>$this->getRequest()->getParam('id')));
    }
    public function getDeleteUrl()
    {
       return $this->getUrl('*/product/delete',array('id'=>$this->getRequest()->getParam('id')));
    }
    public function getResetUrl()
    {
       
    }
    
}