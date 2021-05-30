<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()->newTable($installer->getTable('order/order_item'))
->addColumn('order_item_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
        'primary'=>true,
        'identity'=>true,
        'nullable'=>false
    ),'orderItemId')
    ->addColumn('order_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
        'nullable'=>false
    ),'OrderId')
    ->addColumn('product_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
        'nullable'=>false
    ),'ProductId')
    ->addColumn('quantity',
		Varien_Db_Ddl_Table::TYPE_INTEGER, null,array(
			'nullable' => false,
        ), 'Quantity'
	)
	->addColumn('price',
		Varien_Db_Ddl_Table::TYPE_DECIMAL, null,array(
			'nullable' => false,
        ), 'Price'
	)
	->addColumn('discount',
		Varien_Db_Ddl_Table::TYPE_DECIMAL, null,array(
			'nullable' => true,
        ), 'Discount'
	)
	->addColumn('created_at',
		Varien_Db_Ddl_Table::TYPE_DATETIME, null,array(
			'nullable' => true,
        ),'createdAt'
	);
 
$installer->getConnection()->createTable($table);
$installer->endSetup();