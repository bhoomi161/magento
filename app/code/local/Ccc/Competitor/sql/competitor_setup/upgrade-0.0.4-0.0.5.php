<?php

$installer = $this;

$query = "ALTER TABLE `competitor_decimal` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `competitor_text` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `competitor_int` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `competitor_datetime` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `competitor_varchar` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `competitor_char` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$installer->endSetup();


?>