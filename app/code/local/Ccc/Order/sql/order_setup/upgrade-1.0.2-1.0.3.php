<?php

$installer = $this;
$installer->startSetup();

$installer->run("ALTER TABLE `order_address` DROP COLUMN `address_type`");
$installer->run("ALTER TABLE `order_address` ADD COLUMN `address_type` text");

$installer->endSetup();