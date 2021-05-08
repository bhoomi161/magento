<?php 

$installer = $this;
$installer->startSetup();

$installer->run("alter table vendor_product add column vendor_id int(10)");

$installer->endSetup();

?>