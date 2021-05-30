<?php 

class Ccc_Order_Block_Adminhtml_Order_Order_Main_BillingAddress extends Mage_Adminhtml_Block_Template
{
    protected $order = null;
	public function __construct()
	{
		$this->setTemplate('order/order/main/billingaddress.phtml');
	}
    public function setOrder(Ccc_Order_Model_Order $order)
    {
        $this->order = $order;
        return $this;
    }
    public function getOrder()
    {
       if(!$this->order){
           throw new Exception('Order not found');
       }
       return $this->order;
    }
    public function getBillingAddress()
    {
        return $this->getOrder()->getBillingAddress();
    }
}


?>