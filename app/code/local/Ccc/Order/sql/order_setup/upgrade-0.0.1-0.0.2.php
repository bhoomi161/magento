<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()->newTable($installer->getTable('order/cart_address'))
->addColumn('cart_address_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
        'primary'=>true,
        'identity'=>true,
        'nullable'=>false
    ),'cartAddressId')
    ->addColumn('cart_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
        'nullable'=>false
    ),'cartId')
    ->addColumn('address_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
        'nullable'=>false
    ),'addressId')
    ->addColumn('address_type',
    Varien_Db_Ddl_Table::TYPE_TINYINT,null,array(
        'nullable'=>false
    ),'addressType')
    ->addColumn('address',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,255,array(
        'nullable'=>false
    ),'address')
    ->addColumn('city',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,80,array(
        'nullable'=>false
    ),'city')
    ->addColumn('state',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,80,array(
        'nullable'=>false
    ),'state')
    ->addColumn('country',
    Varien_Db_Ddl_Table::TYPE_VARCHAR,80,array(
        'nullable'=>false
    ),'country')
    ->addColumn('zipcode',
    Varien_Db_Ddl_Table::TYPE_INTEGER,6,array(
        'nullable'=>false
    ),'zipcode')
    ->addColumn('same_as_billing',
    Varien_Db_Ddl_Table::TYPE_TINYINT,null,array(
        'nullable'=>false
    ),'sameAsBilling');

    
$installer->getConnection()->createTable($table);
$installer->endSetup();