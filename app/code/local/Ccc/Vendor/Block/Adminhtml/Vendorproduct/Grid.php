<?php

class Ccc_Vendor_Block_Adminhtml_Vendorproduct_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('vendorproductGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('vendor_filter');

    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();

        $collection = Mage::getModel('vendor/product')->getCollection()
            ->addAttributeToSelect('vendor_id')
            ->addAttributeToSelect('product_id')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price')
            ->addAttributeToSelect('weight');

        
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $collection->joinAttribute(
            'name',
            'vendor_product/name',
            'entity_id',
            null,
            'inner',
            $adminStore
        );

        $collection->joinAttribute(
            'sku',
            'vendor_product/sku',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'price',
            'vendor_product/price',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'weight',
            'vendor_product/weight',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
       
        $collection->joinAttribute(
            'id',
            'vendor_product/entity_id',
            'entity_id',
            null,
            'inner',
            $adminStore
        );

        $requestCollection = Mage::getModel('vendor/product_request')->getCollection();
        //$requestCollection->addFilter('approve_status','pending');
        $varienCollection = new Varien_Data_Collection();

        foreach($requestCollection->getData() as $request){
            foreach($collection->getData() as $product){
                if($request['product_id'] == $product['entity_id']){
                    $array = array_merge($request,$product);
                }
            }
            $rowObj = new Varien_Object();
            $rowObj->setData($array);
            $varienCollection->addItem($rowObj);
        }
       
        $this->setCollection($varienCollection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header' => Mage::helper('vendor')->__('Product Id'),
                'width'  => '50px',
                'index'  => 'id',
            ));
        $this->addColumn('vendor_id',
            array(
                'header' => Mage::helper('vendor')->__('Vendor Id'),
                'width'  => '50px',
                'index'  => 'vendor_id',
            ));
        
        $this->addColumn('name',
            array(
                'header' => Mage::helper('vendor')->__('Name'),
                'width'  => '50px',
                'index'  => 'name',
            ));

        
        $this->addColumn('sku',
            array(
                'header' => Mage::helper('vendor')->__('Sku'),
                'width'  => '50px',
                'index'  => 'sku',
            ));

        $this->addColumn('price',
            array(
                'header' => Mage::helper('vendor')->__('Price'),
                'width'  => '50px',
                'index'  => 'price',
            ));
        $this->addColumn('weight',
            array(
                'header' => Mage::helper('vendor')->__('Weight'),
                'width'  => '50px',
                'index'  => 'weight',
            ));

        $this->addColumn('request_type',
            array(
                'header' => Mage::helper('vendor')->__('Request Type'),
                'width'  => '50px',
                'index'  => 'request_type',
            ));
        $this->addColumn('approve_status',
            array(
                'header' => Mage::helper('vendor')->__('Entity Type'),
                'width'  => '50px',
                'index'  => 'approve_status',
            ));

        $this->addColumn('action',
            array(
                'header'   => Mage::helper('vendor')->__('Action'),
                'width'    => '50px',
                'type'     => 'action',
                'getter'   => 'getId',
                'actions'  => array(
                    array(
                        'caption' => Mage::helper('vendor')->__('Approve'),
                        'url'     => array(
                            'base' => '*/*/approve',
                        ),
                        'field'   => 'id',
                    ),
                    array(
                        'caption' => Mage::helper('vendor')->__('Reject'),
                        'url'     => array(
                            'base' => '*/*/reject',
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
