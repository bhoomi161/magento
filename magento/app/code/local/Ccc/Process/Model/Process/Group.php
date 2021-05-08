<?php

class Ccc_Process_Model_Process_Group extends Mage_Core_Model_Abstract{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('process/process_group');
    }
}