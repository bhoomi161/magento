<?php

class Ccc_Process_Model_Resource_Process_Type extends Mage_Core_Model_Resource_Db_Abstract{
    public function _construct()
    {
        $this->_init('process/process_type','process_type_id');
    }
}