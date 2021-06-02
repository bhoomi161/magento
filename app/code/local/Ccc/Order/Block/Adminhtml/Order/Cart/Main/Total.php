<?php

class Ccc_Order_Block_Adminhtml_Order_Cart_Main_Total extends Mage_Adminhtml_Block_Template {
    protected $cart = null;
    protected $subtotal = null;

    protected $totals = [
        'subtotal' => 0,
        'shipping_amount' =>0,
        'grand_total' =>0
    ];
    public function _construct()
    {
        $this->setTemplate('order/cart/main/total.phtml');
    }
    public function setCart(Ccc_Order_Model_Cart $cart)
    {
        $this->cart = $cart;
        return $this;
    }
    public function getCart()
    {
       if(!$this->cart){
           throw new Exception('Cart not found');
       }
       return $this->cart;
    }
    public function calculateSubTotal()
    {
       $items = $this->cart->getCartItems();
       foreach($items->getData() as $key => $item){
           $this->totals['subtotal'] += $item['quantity']*$item['price'];
       }
    }
    public function getShippingAmount()
    {
       $this->totals['shipping_amount'] = $this->cart->getShippingAmount();
        
    }
    public function calculateGrandTotal()
    {
        $this->totals['grand_total'] = $this->totals['subtotal'] + $this->totals['shipping_amount'];
    }
    public function calculateTotals()
    {
        $this->calculateSubTotal();
        $this->getShippingAmount();
        $this->calculateGrandTotal();
        return $this->totals;
    }
   
}