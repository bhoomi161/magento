<?php

class Ccc_Order_Block_Adminhtml_Order_Order_Main_Shipment extends Mage_Adminhtml_Block_Template{
    public function __construct()
    {
        $this->setTemplate('order/order/main/shipment.phtml');
    }
    public function getShipmentMethods()
    {
        $shipmentMethods = Mage::getSingleton('shipping/config')->getActiveCarriers();
        return $shipmentMethods;
    }
}