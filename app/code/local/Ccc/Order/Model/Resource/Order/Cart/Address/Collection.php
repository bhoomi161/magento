<?php

class Ccc_Order_Model_Resource_Order_Cart_Address_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{
    public function _construct()
    {
        $this->_init('order/order_cart_address');
    }
}