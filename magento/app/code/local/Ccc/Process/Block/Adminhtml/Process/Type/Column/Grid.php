<?php

class Ccc_Process_Block_Adminhtml_Process_Type_Column_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    public function __construct()
    {
        parent::__construct();
        $this->setId('processTypeColumnGrid');
        $this->setParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('process/process_type_column')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
   
    protected function _prepareColumns()
    {
        $this->addColumn('column_id',array(
            'header'=>Mage::helper('process')->__('ID Number'),
            'align' =>'right',
            'width' =>'50px',
            'type'  => 'number',
            'index' =>'column_id'
        ));
        $this->addColumn('process_id',array(
            'header'=>Mage::helper('process')->__('Process Id'),
            'align' =>'left',
            'index' =>'process_id'
        ));
        $this->addColumn('name',array(
            'header'=>Mage::helper('process')->__('Name'),
            'align' =>'left',
            'index' =>'name'
        ));
        $this->addColumn('sample',array(
            'header'=>Mage::helper('process')->__('Sample'),
            'align' =>'left',
            'index' =>'sample'
        ));
        $this->addColumn('default',array(
            'header'=>Mage::helper('process')->__('Default'),
            'align' =>'left',
            'index' =>'default'
        ));
        $this->addColumn('is_required',array(
            'header'=>Mage::helper('process')->__('Is Required'),
            'align' =>'right',
            'index' =>'is_required'
        ));
        $this->addColumn('backend_type',array(
            'header'=>Mage::helper('process')->__('Backend Type'),
            'align' =>'right',
            'index' =>'backend_type'
        ));
        $this->addColumn('sort_order',array(
            'header'=>Mage::helper('process')->__('Sort Order'),
            'align' =>'right',
            'index' =>'sort_order'
        ));

        return parent::_prepareColumns();

    }
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
    }


}