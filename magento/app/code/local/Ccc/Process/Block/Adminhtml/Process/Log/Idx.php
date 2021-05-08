<?php

class Ccc_Process_Block_Adminhtml_Process_Log_Idx extends Mage_Adminhtml_Block_Widget_Grid_Container{
    public function __construct()
    {
        $this->_blockGroup = 'process';
        $this->_controller = 'adminhtml_process_log_idx';
        $this->_headerText = 'Process Log Idx Grid';
        $this->_addButtonLabel = $this->__('Add New Process Group');
        parent::__construct();
    }
}
?>