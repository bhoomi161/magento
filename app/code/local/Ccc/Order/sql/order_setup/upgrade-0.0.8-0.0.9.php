<?php 
$installer = $this;
$installer->startSetup();
$installer->run("ALTER TABLE {$installer->getTable('order/cart_item')} ADD name varchar(50);");

$installer->endSetup();

?>