<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()
->addForeignKey(
    $installer->getFkName(
        'order/cart_item',
        'cart_id',
        'order/cart',
        'cart_id'
    ),    
    $installer->getTable('order/cart_item'),
    'cart_id',
    $installer->getTable('order/cart'),
    'cart_id',

    Varien_Db_Ddl_Table::ACTION_CASCADE,
    Varien_Db_Ddl_Table::ACTION_CASCADE
);

$installer->getConnection()
->addForeignKey(
    $installer->getFkName(
        'order/order_address',
        'order_id',
        'order/order',
        'order_id'
    ),    
    $installer->getTable('order/order_address'),
    'order_id',
    $installer->getTable('order/order'),
    'order_id',

    Varien_Db_Ddl_Table::ACTION_CASCADE,
    Varien_Db_Ddl_Table::ACTION_CASCADE
);
$installer->getConnection()
->addForeignKey(
    $installer->getFkName(
        'order/order_item',
        'order_id',
        'order/order',
        'order_id'
    ),    
    $installer->getTable('order/order_item'),
    'order_id',
    $installer->getTable('order/order'),
    'order_id',

    Varien_Db_Ddl_Table::ACTION_CASCADE,
    Varien_Db_Ddl_Table::ACTION_CASCADE
);
$installer->endSetup();