<?php

class Ccc_Process_Model_Resource_Process_Type_Column extends Mage_Core_Model_Resource_Db_Abstract{
    public function _construct()
    {
        $this->_init('process/process_type_column','column_id');
    }
}