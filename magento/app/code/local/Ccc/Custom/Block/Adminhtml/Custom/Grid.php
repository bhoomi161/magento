<?php

class Ccc_Custom_Block_Adminhtml_Custom_Grid extends Mage_Adminhtml_Block_Widget_Grid{

    public function __construct()
    {
        parent::__construct();
        $this->setId('customGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('custom_filter');

    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();

        $collection = Mage::getModel('custom/custom')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price')
            ->addAttributeToSelect('quantity')
            ->addAttributeToSelect('status')
            ->addAttributeToSelect('brand')
            ->addAttributeToSelect('color');

        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $collection->joinAttribute(
            'name',
            'custom/name',
            'entity_id',
            null,
            'inner',
            $adminStore
        );

        $collection->joinAttribute(
            'sku',
            'custom/sku',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'price',
            'custom/price',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'quantity',
            'custom/quantity',
            'entity_id',
            null,
            'inner',
            $adminStore
        );

        $collection->joinAttribute(
            'status',
            'custom/status',
            'entity_id',
            null,
            'inner',
            $adminStore
        );

        $collection->joinAttribute(
            'color',
            'custom/color',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'brand',
            'custom/brand',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'id',
            'custom/entity_id',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
       
        $this->addColumn('id',
            array(
                'header' => Mage::helper('custom')->__('id'),
                'width'  => '50px',
                'index'  => 'id',
            ));
        $this->addColumn('name',
            array(
                'header' => Mage::helper('custom')->__('Name'),
                'width'  => '50px',
                'index'  => 'name',
            ));
            $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('custom/custom')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();

        $this->addColumn('set_name',
            array(
                'header'=> Mage::helper('custom')->__('Attrib. Set Name'),
                'width' => '100px',
                'index' => 'attribute_set_id',
                'type'  => 'options',
                'options' => $sets,
        ));
        $this->addColumn('sku',
        array(
            'header' => Mage::helper('custom')->__('SKU'),
            'width'  => '50px',
            'index'  => 'sku',
        ));

        $this->addColumn('quantity',
            array(
                'header' => Mage::helper('custom')->__('Qty'),
                'width'  => '50px',
                'index'  => 'quantity',
            ));

        $this->addColumn('status',
            array(
                'header' => Mage::helper('custom')->__('status'),
                'width'  => '50px',
                'index'  => 'status',
            ));

        $this->addColumn('action',
            array(
                'header'   => Mage::helper('custom')->__('Action'),
                'width'    => '50px',
                'type'     => 'action',
                'getter'   => 'getId',
                'actions'  => array(
                    array(
                        'caption' => Mage::helper('custom')->__('Delete'),
                        'url'     => array(
                            'base' => '*/*/delete',
                        ),
                        'field'   => 'id',
                    ),
                ),
                'filter'   => false,
                'sortable' => false,
            ));

        parent::_prepareColumns();
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'store' => $this->getRequest()->getParam('store'),
            'id'    => $row->getId())
        );
    }
}