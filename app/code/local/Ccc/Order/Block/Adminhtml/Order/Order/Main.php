<?php

class Ccc_Order_Block_Adminhtml_Order_Order_Main extends Mage_Adminhtml_Block_Template{
    protected $order = null;
    public function _construct()
    {
        $this->setTemplate('order/order/main.phtml');
    }
    public function setOrder(Ccc_Order_Model_Order $order)
    {
        $this->order = $order;
        return $this;
    }
    public function getOrder()
    {
        if(!$this->order){
            return false;
        }
        return $this->order;
    }
}