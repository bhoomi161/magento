<?php

class Ccc_Vendor_Block_Marketplace_Product_Grid extends Mage_Core_Block_Template{
    public function __construct()
    {
        parent::__construct();
        $this->setId('productGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('product_filter');
    }
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
    
    public function getProducts()
    {
        $store = $this->_getStore();
        $collection = Mage::getModel('vendor/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('attribute_set_id');
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            //$collection->addStoreFilter($store);
            $collection->joinAttribute(
                'name',
                'vendor_product/name',
                'entity_id',
                null,
                'inner',
                $adminStore
            );
            $collection->joinAttribute(
                'sku',
                'vendor_product/sku',
                'entity_id',
                null,
                'inner',
                $adminStore
            );
            $collection->joinAttribute(
                'status',
                'vendor_product/status',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'visibility',
                'vendor_product/visibility',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'price',
                'vendor_product/price',
                'entity_id',
                null,
                'left',
                $store->getId()
            );
            
        $collection->addFilter('vendor_id', ['eq' => Mage::getSingleton('vendor/session')->getVendor()->getId()]);
        return $collection;
    }

    

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl(
            'vendor/product/edit',
             array(
                'id' => $row['entity_id']
            )
        );
    }
    public function getAddUrl()
    {
        return $this->getUrl('vendor/product/new');
    }

    public function getEditUrl()
    {
        return $this->getUrl('vendor/product/edit',array('product_id',$this->getRequest()->getParam('product_id')));
    }
    public function getDeleteUrl($row)
    {
       return $this->getUrl('*/product/delete',array('id'=>$row['entity_id']));
    }
    public function getStatus($id)
    {
        $conn = Mage::getSingleton('core/resource')->getConnection('core_write');
        $sql = "select `approve_status` from `vendor_product_request` where product_id = $id";
        $data = $conn->fetchRow($sql);
        return $data['approve_status'];
    }
}