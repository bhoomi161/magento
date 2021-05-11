<?php

$installer = $this;

$installer->startSetup();

$default_attribute_set_id = Mage::getModel('eav/entity_setup','core_setup')
    						->getAttributeSetId('competitor', 'Default');

$this->run("UPDATE `eav_entity_type` SET `default_attribute_set_id` = {$default_attribute_set_id} WHERE `entity_type_code` = 'competitor'");

$installer->endSetup();
