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
	public function newAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('order');
		$this->renderLayout();
	}
	public function gridAction()
    {
        $this->getResponse()
            ->setBody($this->getLayout()->createBlock('order/adminhtml_order_cart_main_product_grid')->toHtml());
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

	protected function _getCart($id = NULL){
		try{
			if(!$id){
				$id = $this->getRequest()->getParam('id');
			}
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
			if($data['street']=='' || $data['city']=='' || $data['postcode']=='' || $data['region']=='' || $data['country']=='' )
			{
					throw new Exception('Please fill all the fields of billing address');
			}

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
				if($data['street']=='' || $data['city']=='' || $data['postcode']=='' || $data['region']=='' || $data['country']=='' )
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
	public function saveCustomerAction()
	{
		try{
			$data = $this->getRequest()->getPost('order');
			$account = $data['account'];
			$billingAddress = $data['billing_address'];
			$shippingAddress = $data['shipping_address'];

			if($account['email']=='' || $account['firstname']=='' || $account['lastname']==''){
				throw new Exception("Fill all the required fields of account information");
			}
			if($billingAddress['firstname']== '' || $billingAddress['lastname']== '' ||$billingAddress['street']== '' || $billingAddress['firstname']== '' ||$billingAddress['city']== '' || $billingAddress['country']== '' ||$billingAddress['region']== '' || $billingAddress['postcode']== '' ||$billingAddress['telephone']== ''){
				throw new Exception("Fill all the required fields of billing address");
			}
			
			$customerModel = Mage::getModel('customer/customer');
			$customerModel->addData($account);
			if(!$customerModel->save()){
				throw new Exception("Some problem in saving customer information");
			}
			$customerId = $customerModel->getId();
			$cart = $this->_getCart($customerId);

			$customer = $cart->getCustomer();

			$cartBillingAddress = $cart->getBillingAddress();
			$cartShippingAddress = $cart->getShippingAddress();

			$customerBillingAddress = $cart->getCustomer()->getBillingAddress();
			$customerShippingAddress = $cart->getCustomer()->getShippingAddress();

			$cartBillingAddress->addData($billingAddress)
								->setCartId($cart->getId())
								->setAddressType(Ccc_Order_Model_Cart_Address::ADDRESS_TYPE_BILLING);
			if(!$cartBillingAddress->save()){
				throw new Exception("Some problem in saving cart billing address");
			}
			if($billingAddress['save_in_address_book']){
				$customerBillingAddress->addData($billingAddress)
										->setParentId($customer->getId())
										->setEntityTypeId(2);
				if(!$customerBillingAddress->save()){
					throw new Exception('Some problem in saving customer billing address');
				}
			}

			if($shippingAddress['shipping_as_billing']){
				$cartBillingAddressData = $cartBillingAddress->getData();
				unset($cartBillingAddressData['cart_address_id']);
				$cartShippingAddress->addData($cartBillingAddressData)
									->setCartId($cart->getId())
									->setAddressType(Ccc_Order_Model_Cart_Address::ADDRESS_TYPE_SHIPPING);
				if(!$cartShippingAddress->save()){
					throw new Exception("Some problem in saving cart shipping address");
				}
				if($shippingAddress['save_in_address_book']){
					$customerShippingAddress->addData($customerBillingAddress->getData());
					if(!$customerShippingAddress->save()){
						throw new Exception("Some problem in saving customer shipping address");
					}
				}
			}else{
				if($shippingAddress['firstname']== '' || $shippingAddress['lastname']== '' ||$shippingAddress['street']== '' || $shippingAddress['firstname']== '' ||$shippingAddress['city']== '' || $shippingAddress['country']== '' ||$shippingAddress['region']== '' || $shippingAddress['postcode']== '' ||$shippingAddress['telephone']== ''){
					throw new Exception("Fill all the required fields of shipping address");
				}
				$cartShippingAddress->addData($shippingAddress)
									->setCartId($cart->getId())
									->setAddressType(Ccc_Order_Model_Cart_Address::ADDRESS_TYPE_SHIPPING);
				if(!$cartShippingAddress->save()){
					throw new Exception("Some problem in saving cart shipping address");
				}
				if($shippingAddress['save_in_address_book']){
					if(!$customerBillingAddress->getData()){
						throw new Exception("First save the customer billing address");
					}
					$customerShippingAddress->addData($customerBillingAddress->getData())
											->setParentId($customer->getId())
											->setEntityTypeId(2);
					if(!$customerShippingAddress->save()){
						throw new Exception("Some problem in saving customer shipping address");
					}
				}
			}
			$this->_redirect('*/*/customergrid');
		}
		catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*/new');

		}
		

	}			
}