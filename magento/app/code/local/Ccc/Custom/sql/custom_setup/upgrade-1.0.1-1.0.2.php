<?php
$installer = $this;
$installer->startSetup();
$installer->removeAttribute(Ccc_Custom_Model_Resource_Custom::ENTITY,'phoneNo');

$installer->endSetup();
?>