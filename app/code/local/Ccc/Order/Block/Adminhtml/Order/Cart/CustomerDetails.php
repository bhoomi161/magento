<?php


class Ccc_Order_Block_Adminhtml_Order_Cart_CustomerDetails extends Mage_Adminhtml_Block_Template {
    public function _construct()
    {
        $this->setTemplate('order/cart/customerdetails.phtml');
    }
    public function getCustomerDetails()
    {
        $customerId = $this->getRequest()->getParam('id');
        $collection = Mage::getModel('customer/customer')->getCollection()
            ->addFieldToFilter('entity_id',array("eq"=>$customerId));
        $collection->addNameToSelect();   
        return $collection;
    }
}