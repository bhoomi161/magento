<?php

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$installer->getTable('ccc_user/data')} MODIFY  `created_date` datetime(6) ");


$installer->endSetup();

?>