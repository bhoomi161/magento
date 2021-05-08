<?php

class Ccc_User_Block_Adminhtml_User_Grid extends Mage_Adminhtml_Block_Widget_Grid{

    public function __construct()
    {
        parent::__construct();
        $this->setId('userGrid');
        $this->setSaveParametersInSession(true);

    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('ccc_user/data')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('data_id',array(
            'header'=>Mage::helper('user')->__('ID Number'),
            'align' =>'right',
            'width' =>'50px',
            'type'  => 'number',
            'index' =>'data_id'
        ));
        $this->addColumn('first_name',array(
            'header'=>Mage::helper('user')->__('First Name'),
            'align' =>'left',
            'index' =>'first_name'
        ));
        $this->addColumn('last_name',array(
            'header'=>Mage::helper('user')->__('Last Name'),
            'align' =>'left',
            'index' =>'last_name'
        ));
        $this->addColumn('mobile',array(
            'header'=>Mage::helper('user')->__('Mobile'),
            'align' =>'left',
            'index' =>'mobile'
        ));
        $this->addColumn('email',array(
            'header'=>Mage::helper('user')->__('Email'),
            'align' =>'left',
            'index' =>'email'
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
        $this->addColumn('update_date',array(
            'header'=>Mage::helper('user')->__('Updated Date'),
            'align' =>'right',
            'type'  => 'datetime',
            'index' =>'update_date'
        ));

        return parent::_prepareColumns();

    }
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
    }

}
?>