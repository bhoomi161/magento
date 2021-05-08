<?php

class Ccc_Process_Block_Adminhtml_Process_Type_Column_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_process_type_column';
        $this->_blockGroup = 'process';
        $this->_updateButton('save','label',Mage::helper('process')->__('Save process type column'));
        $this->_updateButton('delete','label',Mage::helper('process')->__('Delete process type column'));

    }
    public function getHeaderText()
    {
        if(Mage::registry('process_type_column_data') && Mage::registry('process_type_column_data')->getId()){
            return Mage::helper('process')->__("Edit process '%s'",$this->escapeHtml(Mage::registry('process_type_column_data')->getTitle()));
        }
        else
        {
            return Mage::helper('process')->__("Add process type column");
        }
    }
}