<?php

$installer = $this;

$installer->startSetup();

$installer->addEntityType(Ccc_Competitor_Model_Resource_Competitor::ENTITY,array(
    'entity_model'=>'competitor/competitor',
    'attribute_model'=>'competitor/attribute',
    'table'=>'competitor/competitor',
    'increment_per_store'=>'0',
    'additional_attribute_table'=>'competitor/eav_attribute',
    'entity_attribute_collection'=>'competitor/competitor_attribute_collection',
));

$this->createEntityTables('competitor');

$installer->endSetup();