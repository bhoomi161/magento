<?php
 
class Ccc_Vendor_Block_Marketplace_Product_Group_Edit_Form extends Mage_Core_Block_Template{
    
    
    public function getGroups()
    {
       $id = $this->getRequest()->getParam('id');
       $collection = Mage::getResourceModel('vendor/product_attribute_group_collection')
        ->addFieldToFilter('attribute_group_id',array("eq"=>$id));
        return $collection;
    }

    public function getGroupId()
    {
        $id = $this->getRequest()->getParam('id');
        $collection = Mage::getResourceModel('vendor/product_attribute_group_collection')
         ->addFieldToFilter('attribute_group_id',array("eq"=>$id));
        return  $collection->getData()[0]['group_id'];
       
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
    
    public function getBackUrl()
    {
        return $this->getUrl('*/*/grid');
    }
    public function getAssignedAttributes()
    {
        $vendorId = Mage::getModel('vendor/session')->getId();
       
        $groupId = $this->getRequest()->getParam('id');
        
        $collection = Mage::getModel('vendor/resource_product_attribute_collection')
        ->addFieldToFilter('attribute_code',array("like"=>'%'.$vendorId));
        $collection->getSelect()
            ->join(
                array('vendor_product_attribute'=>'eav_entity_attribute'),
                'vendor_product_attribute.attribute_id = main_table.attribute_id',
                array('*')
            )
        ->where('vendor_product_attribute.attribute_group_id = ?',$groupId);
        return $collection;
    }
    
    public function getAssignedAttributeOfGroup()
    {
        $vendorId = Mage::getModel('vendor/session')->getId();
        $groupId = $this->getRequest()->getParam('id');
        $collection = Mage::getModel('vendor/resource_product_attribute_collection')
        ->addFieldToFilter('attribute_code',array("like"=>'%'.$vendorId));
        $collection->getSelect()
            ->join(
                array('vendor_product_attribute'=>'eav_entity_attribute'),
                'vendor_product_attribute.attribute_id = main_table.attribute_id',
                array('*')
            );
            return $collection;

    }

    public function getUnassignedAttributes()
    {
        $vendorId = Mage::getModel('vendor/session')->getId();
        $groupId = $this->getRequest()->getParam('id');

        $collection = Mage::getModel('vendor/resource_product_attribute_collection')
        ->addFieldToFilter('attribute_code',array("like"=>'%'.$vendorId));
        
        $assignedAttribute = $this->getAssignedAttributeOfGroup();
        if ($assignedAttribute->getData()) {
            $collection->addFieldToFilter('main_table.attribute_id', array('nin' => $assignedAttribute->getData()));
        }
        return $collection;
        
    }
}