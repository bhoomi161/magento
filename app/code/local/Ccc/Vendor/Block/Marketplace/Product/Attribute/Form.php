<?php
 
class Ccc_Vendor_Block_Marketplace_Product_Attribute_Form extends Mage_Eav_Block_Adminhtml_Attribute_Edit_Options_Abstract{

    public function getGroups()
    {
        $id = Mage::getModel('vendor/session')->getId();
        $collection = Mage::getResourceModel('vendor/product_attribute_group_collection')
            ->addFieldToFilter('entity_id',array("eq"=>$id));
        return $collection;
    
    }
    public function getOptions()
    {   
        $attributeId = $this->getRequest()->getParam('attribute_id');
       
        $conn = Mage::getSingleton('core/resource')->getConnection('core_write');
        $sql = "select `option_id` from `eav_attribute_option` where `attribute_id`= $attributeId";
        
        $optionIds = $conn->fetchAll($sql);
        if($optionIds){
            foreach($optionIds as $key=>$value){
                $optionId = $value['option_id'];
                $sql =  "SELECT `option_id`,`value` FROM `eav_attribute_option_value` WHERE `option_id` = $optionId";
                $options = $conn->fetchAll($sql);
                $optionArray[] = $options[0];

            }
        }
       return $optionArray;
    }
    public function getGroupId()
    {
        $id = $this->getRequest()->getParam('attribute_id');
        if($id){
            $conn = Mage::getSingleton('core/resource')->getConnection('core_write');
            $query = "select `attribute_group_id` from `eav_entity_attribute` where `attribute_id` = $id ";
            $data= $conn->fetchAll($query);
            return $data[0]['attribute_group_id']; 
        }
        
        
    }
    public function getBackUrl()
    {
        return $this->getUrl('*/*/grid');
    }
    public function getSaveUrl()
    {
       return $this->getUrl('*/attribute/save',array('attribute_id'=>$this->getRequest()->getParam('attribute_id')));
    }
    
    public function getAttribute()
    {
        $id = $this->getRequest()->getParam('attribute_id');
        $model = Mage::getModel('vendor/resource_eav_productattribute')->load($id);
        return $model;
        
    }
    public function getEditAttributeUrl()
    {
        return $this->getUrl('*/*/save',array('attribute_id'=>$this->getRequest()->getParam('attribute_id')));
    }

    public function getDeleteAttributeUrl()
    {
        return $this->getUrl('*/*/delete',array('attribute_id'=>$this->getRequest()->getParam('attribute_id')));
    }

}