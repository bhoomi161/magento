<?php

class Ccc_Process_Block_Adminhtml_Process_Log_Idx_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_process_log_idx';
        $this->_blockGroup = 'process';
        $this->_updateButton('save','label',Mage::helper('process')->__('Save process log idx'));
        $this->_updateButton('delete','label',Mage::helper('process')->__('Delete process log idx'));

    }
    public function getHeaderText()
    {
        if(Mage::registry('process_log_idx_data') && Mage::registry('process_log_idx_data')->getId()){
            return Mage::helper('process')->__("Edit process '%s'",$this->escapeHtml(Mage::registry('process_log_idx_data')->getTitle()));
        }
        else
        {
            return Mage::helper('process')->__("Add process log idx");
        }
    }
}