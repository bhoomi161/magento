<?php

class Ccc_Process_Model_Resource_Process_Log extends Mage_Core_Model_Resource_Db_Abstract{
    public function _construct()
    {
        $this->_init('process/process_log','process_log_id');
    }
}