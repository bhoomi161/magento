<?php
 
class Ccc_Vendor_Block_Marketplace_Product_Attribute_Grid extends Mage_Core_Block_Template{


    protected function getAttributes()
    {
        $vendorId = Mage::getModel('vendor/session')->getId();

        $collection = Mage::getResourceModel('vendor/product_attribute_collection')
            ->addFieldToFilter('attribute_code', array('like' => '%' . $vendorId . '%'))->getData();
        return $collection;
    }

    public function getCreateAttributeUrl()
    {
        return $this->getUrl('*/*/new');
    }

    public function getEditAttributeUrl($id)
    {
        return $this->getUrl('*/*/edit',array('attribute_id'=>$id));
    }
}