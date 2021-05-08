<?php

class Ccc_Process_Block_Adminhtml_Process extends Mage_Adminhtml_Block_Widget_Grid_Container{
    public function __construct()
    {
        $this->_blockGroup = 'process';
        $this->_controller = 'adminhtml_process';
        $this->_headerText = 'Process Grid';
        parent::__construct();

        $this->_updateButton('add',null,array(
            'label'     => $this->getAddButtonLabel(),
            'onclick'   => 'object.setUrl(\'' . $this->getCreateUrl() .'\').load()',
            'class'     => 'add',
        ));
    }
}
?>