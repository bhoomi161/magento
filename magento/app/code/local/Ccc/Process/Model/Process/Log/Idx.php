<?php

class Ccc_Process_Model_Process_Log_Idx extends Mage_Core_Model_Abstract{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('process/process_log_idx');
    }
}