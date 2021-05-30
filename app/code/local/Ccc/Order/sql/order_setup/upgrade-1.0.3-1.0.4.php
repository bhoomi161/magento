<?php

$installer = $this;
$installer->startSetup();
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->getConnection()
->addForeignKey(
    $installer->getFkName(
        'order/cart_address',
        'cart_id',
        'order/cart',
        'cart_id'
    ),    
    $installer->getTable('order/cart_address'),
    'cart_id',
    $installer->getTable('order/cart'),
    'cart_id',

    Varien_Db_Ddl_Table::ACTION_CASCADE,
    Varien_Db_Ddl_Table::ACTION_CASCADE
);
$installer->endSetup();