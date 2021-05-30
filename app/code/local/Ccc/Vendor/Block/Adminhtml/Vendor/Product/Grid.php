<?php
class Ccc_Vendor_Block_Adminhtml_Vendor_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    public function __construct()
    {
        parent::__construct();
        $this->setId('productGrid');
        $this->setParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        // $collection = Mage::getModel('ccc_test/data')->getCollection();
        // $this->setCollection($collection);
        // return parent::_prepareCollection();
    }

    protected  function _prepareColumns()
    {
        $this->addColumn('data_id',array(
            'header'=>Mage::helper('test')->__('ID Number'),
            'type'=>'number',
            'align'=>'left',
            'width'=>'50px',
            'index'=>'data_id',
        ));

        $this->addColumn('name',array(
            'header'=>Mage::helper('test')->__('Name'),
            'type'=>'text',
            'index'=>'name',

        ));
        $this->addColumn('email',array(
            'header'=>Mage::helper('test')->__('Email'),
            'type'=>'text',
            'index'=>'email',

        ));
        $this->addColumn('mobile',array(
            'header'=>Mage::helper('test')->__('Mobile NO'),
            'index'=>'mobile',

        ));
        $this->addColumn('status',array(
            'header'=>Mage::helper('user')->__('Status'),
            'align' =>'right',
            'index' =>'status'
        ));
        $this->addColumn('created_date',array(
            'header'=>Mage::helper('user')->__('Created Date'),
            'align' =>'right',
            'type'  => 'datetime',
            'index' =>'created_date'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId()));

    }

}


?>