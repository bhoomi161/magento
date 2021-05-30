<?php

class Ccc_Order_Block_Adminhtml_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    public function __construct()
    {
        parent::__construct();
        $this->setId('order_grid');
        $this->setSaveParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('order/order')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('order_id',array(
            'header'=>Mage::helper('order')->__('ID Number'),
            'align' =>'right',
            'width' =>'50px',
            'type'  => 'number',
            'index' =>'order_id'
        ));
        $this->addColumn('customer_name',array(
            'header'=>Mage::helper('order')->__('Customer Name'),
            'index' =>'customer_name'
        ));
      
        $this->addColumn('total', array(
            'header' => Mage::helper('sales')->__('Grand Total'),
            'index' => 'total',
            'type'  => 'currency',
        ));
        $this->addColumn('discount',array(
            'header'=>Mage::helper('order')->__('Discount'),
            'index' =>'discount'
        ));
        $this->addColumn('created_at',array(
            'header'=>Mage::helper('order')->__('Created At'),
            'align' =>'right',
            'type'  => 'datetime',
            'index' =>'created_at'
        ));
       
        return parent::_prepareColumns();
        
    }
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/showOrder', array('id'=>$row->getId()));
    }
}