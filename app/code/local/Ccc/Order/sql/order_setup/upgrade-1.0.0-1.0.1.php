<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('order_address')}
    CHANGE `address_type` `address_type` text;
");

$installer->endSetup();
?>