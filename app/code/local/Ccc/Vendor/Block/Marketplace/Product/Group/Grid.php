<?php
 
class Ccc_Vendor_Block_Marketplace_Product_Group_Grid extends Mage_Core_Block_Template{
   
    public function getGroups()
    {
       $id = Mage::getModel('vendor/session')->getId();
       $collection = Mage::getResourceModel('vendor/product_attribute_group_collection')
        ->addFieldToFilter('entity_id',array("eq"=>$id));
        return $collection;
    }
    public function getGroupId()
    {
        $id = $this->getRequest()->getParam('id');
        $collection = Mage::getResourceModel('vendor/product_attribute_group_collection')
         ->addFieldToFilter('attribute_group_id',array("eq"=>$id));
        return  $collection->getData()[0]['group_id'];
       
    }
    public function getCreateGroupUrl()
    {
        return $this->getUrl('*/group/new');
    }

    public function getEditGroupUrl()
    {
        $groupId = $this->getGroupId();
        return $this->getUrl('*/group/save',array('attribute_group_id'=>$this->getRequest()->getParam('id'),'group_id'=>$groupId));

    }
    public function getDeleteGroupUrl()
    {
        $groupId = $this->getGroupId();
        return $this->getUrl('*/group/delete',array('attribute_group_id'=>$this->getRequest()->getParam('id'),'group_id'=>$groupId));

    }
}