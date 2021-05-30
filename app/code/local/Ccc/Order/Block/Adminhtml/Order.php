<?php

class Ccc_Order_Block_Adminhtml_Order extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_order';
        $this->_blockGroup = 'order';
        $this->_headerText = Mage::helper('order')->__('Order');
        $this->_addButtonLabel = Mage::helper('order')->__('Add New Order');
        parent::__construct();

    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/adminhtml_order_cart/customergrid');
    }

}
