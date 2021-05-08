<?php

class Ccc_Process_Block_Adminhtml_Process_Type_Column extends Mage_Adminhtml_Block_Widget_Grid_Container{
    public function __construct()
    {
        $this->_blockGroup = 'process';
        $this->_controller = 'adminhtml_process_type_column';
        $this->_headerText = 'Process Type Column Grid';
        parent::__construct();

        $this->_updateButton('add',null,array(
            'label'     => $this->getAddButtonLabel(),
            'onclick'   => 'object.setUrl(\'' . $this->getCreateUrl() .'\').load()',
            'class'     => 'add',
        ));
    }
}
?>