<?php

$installer = $this;
$installer->startSetup();

$query = "ALTER TABLE `vendor_char` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `vendor_int` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `vendor_decimal` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `vendor_datetime` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `vendor_text` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `vendor_product_char` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `vendor_product_int` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `vendor_product_decimal` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `vendor_product_datetime` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `vendor_product_text` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `vendor_product_varchar` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$installer->endSetup();


?>

