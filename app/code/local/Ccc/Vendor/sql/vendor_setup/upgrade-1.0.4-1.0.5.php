<?php

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$installer->getTable('vendor/eav_attribute')} ADD `sort_order` int(10) null");
$installer->endSetup();
?>