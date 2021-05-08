<?php
class Ccc_Custom_Model_Resource_Custom_Collection extends Mage_Catalog_Model_Resource_Collection_Abstract
{
	public function __construct()
	{
		$this->setEntity('custom');
		parent::__construct();
		
	}
}