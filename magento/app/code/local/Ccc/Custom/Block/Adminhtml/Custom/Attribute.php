<?php

class Ccc_Custom_Block_Adminhtml_Custom_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
    	$this->_blockGroup = 'custom';
        $this->_controller = 'adminhtml_custom_attribute';
        $this->_headerText = Mage::helper('custom')->__('Manage Attributes');
        $this->_addButtonLabel = Mage::helper('custom')->__('Add New Attribute');
        parent::__construct();
    }

}
