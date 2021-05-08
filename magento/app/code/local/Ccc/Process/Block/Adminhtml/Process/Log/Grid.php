<?php

class Ccc_Process_Block_Adminhtml_Process_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    public function __construct()
    {
        parent::__construct();
        $this->setId('processGroupGrid');
        $this->setParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('process/process_log')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {
        $this->addColumn('process_log_id',array(
            'header'=>Mage::helper('process')->__('ID Number'),
            'align' =>'right',
            'width' =>'50px',
            'type'  => 'number',
            'index' =>'process_id'
        ));
        $this->addColumn('process_id',array(
            'header'=>Mage::helper('process')->__('Process Id'),
            'align' =>'left',
            'index' =>'process_id'
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