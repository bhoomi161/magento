<?php

class Ccc_Competitor_Block_Adminhtml_Competitor_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container{
    public function __construct()
    {
       $this->_blockGroup = 'competitor';
       $this->_controller = 'adminhtml_competitor_attribute';
       $this->_headerText = 'Manage Attribute';
       parent::__construct();
    }
}