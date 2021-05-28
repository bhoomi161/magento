<?php

class Ccc_Order_Block_Adminhtml_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    public function __construct()
    {
        parent::__construct();
        $this->setId('order_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
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
        $this->addColumn('customer_id',array(
            'header'=>Mage::helper('order')->__('Customer Id'),
            'align' =>'left',
            'index' =>'customer_id'
        ));
        $this->addColumn('discount',array(
            'header'=>Mage::helper('order')->__('Discount'),
            'align' =>'left',
            'index' =>'discount'
        ));
        $this->addColumn('total', array(
            'header' => Mage::helper('sales')->__('Grand Total'),
            'index' => 'total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));
        $this->addColumn('created_at',array(
            'header'=>Mage::helper('order')->__('Created At'),
            'align' =>'right',
            'type'  => 'datetime',
            'index' =>'created_at'
        ));
        $this->addRssList('rss/order/new', Mage::helper('sales')->__('New Order RSS'));
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));
        return parent::_prepareColumns();
        
    }
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
    }
}