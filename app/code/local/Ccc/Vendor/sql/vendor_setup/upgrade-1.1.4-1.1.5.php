<?php 

$installer = $this;
$installer->startSetup();

$installer->run("alter table vendor_product add column sku varchar(12) default null");

$installer->endSetup();

?>