<?php

class Ccc_Competitor_Block_Adminhtml_Competitor_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    public function __construct()
    {
        parent::__construct();
        $this->setId('competitorGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);

    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        
        $store = $this->_getStore();
        $collection = Mage::getModel('competitor/competitor')->getCollection()
                 ->addAttributeToSelect('firstname')
                 ->addAttributeToSelect('lastname');
        
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            $collection->joinAttribute(
                'firstname',
                'competitor/firstname',
                'entity_id',
                null,
                'inner',
                $adminStore
            );
            $collection->joinAttribute(
                'lastname',
                'competitor/lastname',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
           

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
    public function _prepareColumns()
    {
        $this->addColumn('entity_id',
            array(
                'header'=> Mage::helper('competitor')->__('ID'),
                'width' => '50px',
                'type'  => 'number',
                'index' => 'entity_id',
        ));
        $this->addColumn('firstname',
            array(
                'header'=> Mage::helper('competitor')->__('First Name'),
                'index' => 'firstname',
        ));
        $this->addColumn('lastname',
            array(
                'header'=> Mage::helper('competitor')->__('Last Name'),
                'index' => 'lastname',
        ));
    }
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'store'=>$this->getRequest()->getParam('store'),
            'id'=>$row->getEntityId())
        );
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

}