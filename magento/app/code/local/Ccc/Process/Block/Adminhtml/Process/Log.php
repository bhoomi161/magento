<?php

class Ccc_Process_Block_Adminhtml_Process_Log extends Mage_Adminhtml_Block_Widget_Grid_Container{
    public function __construct()
    {
        $this->_blockGroup = 'process';
        $this->_controller = 'adminhtml_process_log';
        $this->_headerText = 'Process Log Grid';
        parent::__construct();
      
    }
}
?>