<?php
class Ccc_Order_Block_Adminhtml_Order_Cart_Main_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid{

    public function __construct(){
        parent::__construct();
        $this->setId('productGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    public function _prepareCollection(){
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('sku')
                ->addAttributeToSelect('name');

        $collection->joinAttribute(
            'name',
            'catalog_product/name',
            'entity_id',
            null,
            'inner'
        );
        
        $collection->joinAttribute(
            'custom_name',
            'catalog_product/name',
            'entity_id',
            null,
            'inner'
        );
        
        $collection->joinAttribute(
            'price',
            'catalog_product/price',
            'entity_id',
            null,
            'inner'
        );
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function _prepareColumns(){
        $this->addColumn('entity_id',
        array(
            'header' => Mage::helper('catalog')->__('Id'),
            'width' => '50px',
            'type' => 'number',
            'index' => 'entity_id'
        ));
        $this->addColumn('name',
        array(
            'header' => Mage::helper('catalog')->__('Name'),
            'index' => 'name'
        ));
       
        $this->addColumn('sku',
        array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'index' => 'sku'
        ));
        $this->addColumn('price',
        array(
            'header' => Mage::helper('catalog')->__('Price'),
            'index' => 'price'
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassAction(){
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('product');

        $this->getMassactionBlock()->addItem('action',array(
            'selected' => true,
            'label' => Mage::helper('order')->__('Add to Cart'),
            'url' => $this->getUrl('*/*/addToCart',array('_current' => true)),
        ));
        return $this;
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
?>