<?php
class Ccc_Custom_Block_Adminhtml_Custom extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'custom';
        $this->_controller = 'adminhtml_custom';
        $this->_headerText = $this->__('Custom Grid');
        parent::__construct();
    }
}