<?php

$this->startSetup();

$this->addEntityType(Ccc_Custom_Model_Resource_Custom::ENTITY,[
    'entity_model'                => 'custom/custom',
    'attribute_model'             => 'custom/attribute',
    'table'                       => 'custom/custom',
    'increment_per_store'         => '0',
    'additional_attribute_table'  => 'custom/eav_attribute',
    'entity_attribute_collection' => '_collection',
]);

$this->createEntityTables('custom');
$this->installEntities();

$default_attribute_set_id = Mage::getModel('eav/entity_setup', 'core_setup')
    						->getAttributeSetId('custom', 'Default');

$this->run("UPDATE `eav_entity_type` SET `default_attribute_set_id` =
 {$default_attribute_set_id} WHERE `entity_type_code` = 'custom'");

$this->endSetup();
