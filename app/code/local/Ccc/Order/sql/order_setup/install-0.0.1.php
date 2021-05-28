<?php

$installer = $this;
$installer->startSetup();

$installer->run("DROP TABLE IF EXISTS `{$installer->getTable('order/cart')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('order/cart_item')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('order/cart_address')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('order/order')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('order/order_address')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('order/order_item')}`;"
);

$table = $installer->getConnection()->newTable($installer->getTable('order/cart'))
        ->addColumn('cart_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
            'primary'=>true,
            'identity'=>true,
            'nullable'=>false
        ),'cartId')
        ->addColumn('customer_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
            'nullable'=>false
        ),'customerId')
        ->addColumn('session_id',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,50,array(
            'nullable'=>false
        ),'sessionId')
        ->addColumn('total',
        Varien_Db_Ddl_Table::TYPE_DECIMAL,null,array(
            'nullable'=>false
        ),'total')
        ->addColumn('discount',
        Varien_Db_Ddl_Table::TYPE_DECIMAL,null,array(
            'nullable'=>false
        ),'discount')
        ->addColumn('payment_code',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,255,array(
            'nullable'=>false
        ),'payment_code')
        ->addColumn('shipment_code',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,255,array(
            'nullable'=>false
        ),'shipment_code')
        ->addColumn('shipping_amount',
        Varien_Db_Ddl_Table::TYPE_DECIMAL,null,array(
            'nullable'=>false
        ),'shipping_amount')
        ->addColumn('created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,null,array(
            'nullable'=>false
        ),'created_at');

$this->getConnection()->createTable($table);
$installer->endSetup();


?>