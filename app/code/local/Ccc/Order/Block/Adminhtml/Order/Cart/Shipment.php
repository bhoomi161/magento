<?php

class Ccc_Order_Block_Adminhtml_Order_Cart_Shipment extends Mage_Adminhtml_Block_Template{
    public function _construct()
    {
        $this->setTemplate('order/cart/shipment.phtml');
    }
    public function getShipmentMethods()
    {
        $shipmentMethods = Mage::getSingleton('shipping/config')->getActiveCarriers();
        return $shipmentMethods;
    }
}