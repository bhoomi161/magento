<?php

class Ccc_Process_Block_Adminhtml_Process_Group_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_process_group';
        $this->_blockGroup = 'process';

        $this->_updateButton('save',null,array(
            'label'     =>'Save Group',
            'onclick'   => "object.setForm('#edit_form').load()",
            'class'     => 'add',
        ));
        $this->_updateButton('delete','label',Mage::helper('process')->__('Delete process group'));

    }
    public function getHeaderText()
    {
        if(Mage::registry('process_group_data') && Mage::registry('process_group_data')->getId()){
            return Mage::helper('process')->__("Edit process '%s'",$this->escapeHtml(Mage::registry('process_group_data')->getTitle()));
        }
        else
        {
            return Mage::helper('process')->__("Add process group");
        }
    }
}