<?php

class Ccc_Process_Block_Adminhtml_Process_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    public function __construct()
    {
        parent::__construct();
        $this->setId('processGrid');
        $this->setParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('process/process')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('process_id',array(
            'header'=>Mage::helper('process')->__('ID Number'),
            'align' =>'right',
            'width' =>'50px',
            'type'  => 'number',
            'index' =>'process_id'
        ));
        $this->addColumn('process_type_id',array(
            'header'=>Mage::helper('process')->__('Process Type Id'),
            'align' =>'left',
            'index' =>'process_type_id'
        ));
        $this->addColumn('group_id',array(
            'header'=>Mage::helper('process')->__('Group Id'),
            'align' =>'left',
            'index' =>'group_id'
        ));
        $this->addColumn('name',array(
            'header'=>Mage::helper('process')->__('Name'),
            'align' =>'left',
            'index' =>'name'
        ));
        $this->addColumn('per_request_count',array(
            'header'=>Mage::helper('process')->__('Per Request Count'),
            'align' =>'left',
            'index' =>'per_request_count'
        ));
        $this->addColumn('request_interval',array(
            'header'=>Mage::helper('process')->__('Request Interval'),
            'align' =>'right',
            'index' =>'request_interval'
        ));
        $this->addColumn('model',array(
            'header'=>Mage::helper('process')->__('Model'),
            'align' =>'right',
            'index' =>'model'
        ));
        $this->addColumn('created_date',array(
            'header'=>Mage::helper('process')->__('Created Date'),
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