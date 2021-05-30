<?php

class Ccc_Order_Adminhtml_Order_CartController extends Mage_Adminhtml_Controller_Action{
    public function indexAction()
	{
		$this->loadLayout();
		$this->_title($this->__('order'))->_title($this->__('Orders'));
        $this->_setActiveMenu('order');
		$cart = $this->_getCart();
		$this->getLayout()->getBlock('cart_index')->setCart($cart);
		$this->renderLayout();
	}
	public function customergridAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('order');
		$this->renderLayout();
	}
	public function addToCartAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception('Customer not found!!');
			}
			$productIds = $this->getRequest()->getParam('product');	
			$cart = $this->_getCart();
			foreach($productIds as $key => $productId){
				$product = Mage::getSingleton('catalog/product')->load($productId);
				$cart->addItemToCart($product,1,true);
			}
			Mage::getSingleton('adminhtml/session')->addSuccess("Item added successfully");
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);		
	}

	protected function _getCart(){
		try{
			$id = $this->getRequest()->getParam('id');
			date_default_timezone_set('Asia/Kolkata');
			$customer = Mage::getModel('customer/customer')->load($id);
			if(!$customer->getId()){
				throw new Exception('Customer not found');
			}
			$cart = Mage::getModel('order/cart');
			$cart->load($id,'customer_id');
			if($cart->getId()){
				return $cart;
			}
			$cart->setCustomerId($id)
				->setCreatedAt(date('Y-m-d H:i:s'))
				->save();
			return $cart;
		}catch(Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}	
	}

	public function updateAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception("Customer not found");
			}
			$data = $this->getRequest()->getPost();
			foreach($data['quantity'] as $cartItemId => $quantity){
				$cartItem = Mage::getModel('order/cart_item')->load($cartItemId);
				if($quantity == 0){
					$cartItem->delete();
				}
				$cartItem->setQuantity($quantity)
					->setBasePrice($quantity*$cartItem->getPrice() - $quantity*$cartItem->getDiscount())
					->save();
		}
		Mage::getSingleton('adminhtml/session')->addSuccess("CartItems updated successfully");

		}catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);
	
	}

	public function deleteAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception("Customer not found");
			}
			$id = $this->getRequest()->getParam('itemid');
			if(!$id){
				Mage::getSingleton('adminhtml/session')->addError("Invalid id");	
			}
			$cartItem = Mage::getModel('order/cart_item')->load($id);
			if(!$cartItem->getId()){
				Mage::getSingleton('adminhtml/session')->addError("Item doesn't exist");	
			}
			if(!$cartItem->delete()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to delete the item");	
			}
			Mage::getSingleton('adminhtml/session')->addSuccess("Item deleted successfully");	

		}
		catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);
	}

	public function saveBillingAddressAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception('Customer not found');
			}
			$data = $this->getRequest()->getPost('billing');
			$cart = $this->_getCart();

			$cartAddress = $this->_getCart()->getBillingAddress();
			
			$cartAddress->addData($data)
			->setCartId($cart->getId())
			->setAddressType(Ccc_Order_Model_Cart_Address::ADDRESS_TYPE_BILLING);

			if(!$cartAddress->save()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to save cart address");	
			}
			$addressBook = $this->getRequest()->getPost();
			if($addressBook['saveBillingAddress']){
				$customerAddress = $this->_getCart()->getCustomer()->getBillingAddress();
				$customerAddress->addData($data)
								->setEntityTypeId(2)
								->setParentId($customerId);
				if(!$customerAddress->save()){
					Mage::getSingleton('adminhtml/session')->addError("Unable to save customer address");
				}
		}
		Mage::getSingleton('adminhtml/session')->addSuccess("Billing Address Saved");	

		}catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);

	}

	public function saveShippingAddressAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception('Customer not found');
			}
			$cart = $this->_getCart();
			$cartAddress = $this->_getCart()->getShippingAddress();
			$data = $this->getRequest()->getPost('shipping');

			$addressBook = $this->getRequest()->getPost();

			if($addressBook['sameAsBilling']){
				$cartBillingAddress = $this->_getCart()->getBillingAddress();
				if(!$cartBillingAddress->getData()){
					throw new Exception("Please save the cart billing address first");	
				}
				$data = $cartBillingAddress->getData();
				unset($data['cart_address_id']);
				$cartAddress->addData($data)
							->setAddressType(Ccc_Order_Model_Cart_Address::ADDRESS_TYPE_SHIPPING);
				if(!$cartAddress->save()){
					Mage::getSingleton('adminhtml/session')->addError("Unable to save cart shipping address");	
				}
				if($addressBook['saveShippingAddress']){
					$customerAddress = $this->_getCart()->getCustomer()->getShippingAddress();
					$customerAddress->addData($data)
									->setEntityTypeId(2)
									->setParentId($customerId);
					if(!$customerAddress->save()){
						Mage::getSingleton('adminhtml/session')->addError("Unable to save customer address");
					}
				}

			}else{
				if($data['street']=='' || $data['city']=='' || $data['postcode']=='' || $data['region']=='' || $data['country_id']=='' )
				{
					throw new Exception('Please fill all the fields of shipping address');
				}
				$cartAddress->addData($data)
				->setCartId($cart->getId())
				->setAddressType(Ccc_Order_Model_Cart_Address::ADDRESS_TYPE_SHIPPING);

				if(!$cartAddress->save()){
					Mage::getSingleton('adminhtml/session')->addError("Unable to save cart address");	
				}
				if($addressBook['saveShippingAddress']){
					$customerAddress = $this->_getCart()->getCustomer()->getShippingAddress();
					$customerAddress->addData($data)
									->setEntityTypeId(2)
									->setParentId($customerId);
					if(!$customerAddress->save()){
						Mage::getSingleton('adminhtml/session')->addError("Unable to save customer address");
					}
				}
			}
			Mage::getSingleton('adminhtml/session')->addSuccess("Shipping Address Saved");	

		}catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);
	}	
	public function savePaymentMethodAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception('Customer not found');
			}
			$paymentmethod = $this->getRequest()->getPost('paymentmethod');
			
			$cartModel = $this->_getCart();
			$cartModel->setPaymentCode($paymentmethod);
			if(!$cartModel->save()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to save");	
			}
			Mage::getSingleton('adminhtml/session')->addSuccess("Payment method saved successfully");	

		}
		catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);
	}
	public function saveShipmentDataAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception('Customer not found');
			}
			$shipmentData = $this->getRequest()->getPost('shippingmethod');
			$data = explode('_',$shipmentData);
			$cartModel = $this->_getCart();
			$cartModel->setShipmentCode($data[0]);
			$cartModel->setShippingAmount($data[1]);
			if(!$cartModel->save()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to save");	
			}
			Mage::getSingleton('adminhtml/session')->addSuccess("shipment method saved successfully");	
		}
		catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);
	}			
}