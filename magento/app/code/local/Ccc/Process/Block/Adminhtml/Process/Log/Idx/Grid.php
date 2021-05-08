<?php

class Ccc_Process_Block_Adminhtml_Process_Log_Idx_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    public function __construct()
    {
        parent::__construct();
        $this->setId('processLogIdxGrid');
        $this->setParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('process/process_log_idx')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id',array(
            'header'=>Mage::helper('process')->__('ID Number'),
            'align' =>'right',
            'width' =>'50px',
            'type'  => 'number',
            'index' =>'entity_id'
        ));
        $this->addColumn('process_id',array(
            'header'=>Mage::helper('process')->__('Process Id'),
            'align' =>'left',
            'index' =>'process_id'
        ));
        $this->addColumn('process_log_id',array(
            'header'=>Mage::helper('process')->__('Log Id'),
            'align' =>'left',
            'index' =>'process_log_id'
        ));
        $this->addColumn('unique_id',array(
            'header'=>Mage::helper('process')->__('unique_id'),
            'align' =>'left',
            'index' =>'unique_id'
        ));
        $this->addColumn('data',array(
            'header'=>Mage::helper('process')->__('Data'),
            'align' =>'left',
            'index' =>'data'
        ));
        
        $this->addColumn('start_time',array(
            'header'=>Mage::helper('process')->__('Start Time'),
            'align' =>'right',
            'type'  => 'datetime',
            'index' =>'start_time'
        ));
        $this->addColumn('end_time',array(
            'header'=>Mage::helper('process')->__('End Time'),
            'align' =>'right',
            'type'  => 'datetime',
            'index' =>'end_time'
        ));

        return parent::_prepareColumns();

    }
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
    }

}