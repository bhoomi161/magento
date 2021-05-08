<?php

$installer = $this;

$query = "ALTER TABLE `custom_datetime` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$query = "ALTER TABLE `custom_char` ADD UNIQUE( `attribute_id`, `store_id`, `entity_id`)";
$installer->getConnection()->query($query);

$installer->endSetup();

?>