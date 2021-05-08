<?php

class Ccc_Custom_Model_Attribute extends Mage_Eav_Model_Attribute
{
    const MODULE_NAME = 'Ccc_Custom';
    const SCOPE_GLOBAL = 1;
    const SCOPE_WEBSITE = 2;


    protected $_eventObject = 'attribute';

    protected function _construct()
    {
        $this->_init('custom/attribute');
    }
    public function getIsGlobal()
    {
        return $this->_getData('is_global');
    }

    public function isScopeGlobal()
    {
        return $this->getIsGlobal() == self::SCOPE_GLOBAL;
    }

    public function isScopeWebsite()
    {
        return $this->getIsGlobal() == self::SCOPE_WEBSITE;
    }

    public function isScopeStore()
    {
        return !$this->isScopeGlobal() && !$this->isScopeWebsite();
    }
}
