<?php

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$installer->getTable('ccc_user/data')} ADD `update_date` datetime(6) null");

$installer->run("
ALTER TABLE {$installer->getTable('ccc_user/data')} ADD `password` varchar(255) null");

$installer->endSetup();

?>