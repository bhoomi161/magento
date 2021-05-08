<?php

$installer = $this;

$query = "ALTER TABLE `custom_decimal` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `custom_text` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `custom_int` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$installer->endSetup();


?>