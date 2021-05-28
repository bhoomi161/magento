<?php

class Ccc_Order_Model_Order_Cart extends Mage_Core_Model_Abstract{
    protected $customer = null;
    public function _construct()
    {
      $this->_init('order/order_cart');
    }
    public function setCustomer(Mage_Customer_Model_Customer $customer)
    {
      $this->customer = $customer;
      return $this;
    }
    public function getCustomer()
    {
      if($this->customer){
        return $this->customer;
      }
      $customer = Mage::getModel('customer/customer')->load($this->customer_id);
      $this->setCustomer($customer);
      return $this->customer;
    }
    public function getCartItems()
    { 
      $collection = Mage::getModel('order/order_cart_item')->getCollection();
      $collection->addFieldToFilter('cart_id',['eq' => $this->cart_id]);
      return $collection; 
    }
    public function addItemToCart($product,$quantity,$addMode = false)
    {
      $cartId = Mage::getModel('order/session')->getCartId();
      $productId = $product->entity_id;
  
      $collection = Mage::getModel('order/order_cart_item')->getCollection();
      $collection->addFieldToFilter('cart_id', ['eq' => $cartId]);
      $collection->addFieldToFilter('product_id', ['eq' => $productId]);
      $id = $collection->getData()[0]['cart_item_id'];
      
      $cartItem = Mage::getModel('order/order_cart_item');
      $data = $cartItem->load($id);
     
      if($data->getData()){
        $data->quantity += $quantity;
        $data->basePrice =($product->price*$data->quantity)-($data->quantity*$product->discount);
        $cartItem->save();
        return true;
      }
      
          date_default_timezone_set('Asia/Kolkata');
          $cartItem->cartId = $cartId;
          $cartItem->productId = $product->entity_id;
          $cartItem->price = $product->price;
          $cartItem->quantity = $quantity;
          $cartItem->discount = $product->discount;
          $cartItem->basePrice = $product->price - $product->discount;
          $cartItem->createdDate = date('Y-m-d H:i:s');
          $cartItem->save();
          return true;
    }



}