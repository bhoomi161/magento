<?php

class Ccc_Process_Model_Process extends Mage_Core_Model_Abstract{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('process/process');
    }
}