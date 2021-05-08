<?php
class Ccc_Custom_Model_Resource_Custom extends Mage_Eav_Model_Entity_Abstract
{
	const ENTITY = 'custom';
	
	public function __construct()
	{
		$this->setType(self::ENTITY)
			 ->setConnection('core_read', 'core_write');

	   parent::__construct();
    }

}