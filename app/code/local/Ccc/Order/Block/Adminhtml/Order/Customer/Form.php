<?php 

class Ccc_Order_Block_Adminhtml_Order_Customer_Form extends Mage_Adminhtml_Block_Template
{
	public function _construct()
	{
		$this->setTemplate('order/customer/form.phtml');
	}
    public function getCountries()
    {
        return Mage::getModel('adminhtml/system_config_source_country')->toOptionArray();
    }
}


?>