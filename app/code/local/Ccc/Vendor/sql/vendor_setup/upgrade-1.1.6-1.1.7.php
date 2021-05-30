<?php

$installer = $this;
$installer->startSetup();

$installer->run("update eav_entity_type set entity_table='vendor/product' where entity_type_code='vendor_product'");
$installer->endSetup();