<?php  

class Ccc_Order_Block_Adminhtml_Order_Order_Main_Product extends Mage_Adminhtml_Block_Template
{
	public function __construct()
	{
        
		$this->setTemplate('order/order/main/product.phtml');
	}
	public function getProductName($id)
	{
		$product = Mage::getModel('catalog/product')->load($id);
		return $product->getName();
	}

}

?>