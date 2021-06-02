<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('order_address')}
    CHANGE `address` `street` text;
");

$installer->run("
ALTER TABLE {$this->getTable('order_address')}
    CHANGE `country` `country_id` text;
");

$installer->run("
ALTER TABLE {$this->getTable('order_address')}
    CHANGE `zipcode` `postcode` int(11);
");


$installer->run("
ALTER TABLE {$this->getTable('order_address')}
    CHANGE `state` `region` text;
");

$installer->run("
ALTER TABLE {$this->getTable('cart_address')}
    CHANGE `address_type` `address_type` text;
");
$installer->endSetup();
?>