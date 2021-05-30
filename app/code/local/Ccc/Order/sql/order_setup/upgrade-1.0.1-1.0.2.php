<?php

$installer = $this;
$installer->startSetup();

$installer->run("ALTER TABLE {$this->getTable('order/cart')} DROP COLUMN `session_id`");

$installer->run("ALTER TABLE {$this->getTable('order/cart_address')} DROP COLUMN `address_id`");

$installer->run("ALTER TABLE {$this->getTable('order/cart_address')} DROP COLUMN `same_as_billing`");

$installer->run("ALTER TABLE {$this->getTable('order/order_address')} DROP COLUMN `address_id`");

$installer->run("ALTER TABLE {$this->getTable('order/order_address')} DROP COLUMN `same_as_billing`");

$installer->endSetup();

?>