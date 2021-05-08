<?php

class Ccc_Process_Block_Adminhtml_Process_Group_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    public function __construct()
    {
        parent::__construct();
        $this->setId('processGroupGrid');
        $this->setParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('process/process_group')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {
        $this->addColumn('group_id',array(
            'header'=>Mage::helper('process')->__('ID Number'),
            'align' =>'right',
            'width' =>'50px',
            'type'  => 'number',
            'index' =>'group_id'
        ));
        $this->addColumn('name',array(
            'header'=>Mage::helper('process')->__('Name'),
            'align' =>'right',
            'width' =>'50px',
            'index' =>'name'
        ));
    }
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'id'    => $row->getId())
        );
    }
}