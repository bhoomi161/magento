<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()->newTable($installer->getTable('order/cart_item'))
->addColumn('cart_item_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
        'primary'=>true,
        'identity'=>true,
        'nullable'=>false
    ),'cartItemId')
    ->addColumn('cart_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
        'nullable'=>false
    ),'cartId')
    ->addColumn('product_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
        'nullable'=>false
    ),'Product Id')
    ->addColumn('quantity',
    Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
        'nullable'=>false
    ),'Quantity')
    ->addColumn('base_price',
    Varien_Db_Ddl_Table::TYPE_DECIMAL,null,array(
        'nullable'=>false
    ),'Base Price')
    ->addColumn('price',
    Varien_Db_Ddl_Table::TYPE_DECIMAL,null,array(
        'nullable'=>false
    ),'price')
    ->addColumn('discount',
    Varien_Db_Ddl_Table::TYPE_DECIMAL,null,array(
        'nullable'=>false
    ),'Discount')
    ->addColumn('created_at',
    Varien_Db_Ddl_Table::TYPE_DATETIME,null,array(
        'nullable'=>false
    ),'createdAt');
    
$installer->getConnection()->createTable($table);
$installer->endSetup();