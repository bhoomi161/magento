<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
        ->newTable($installer->getTable('ccc_user/data'))
        ->addColumn('data_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,null,
        array(
            'nullable'=>false,
            'identity'=>true,
            'unsigned'=>true,
            'primary'=>true,

        ),'Id'
        )
        ->addColumn('first_name',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
        array(
            'nullable'=>false,
        ),'firstName'
        )
        ->addColumn('last_name',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
        array(
            'nullable'=>false,
        ),'lastName'
        )
        ->addColumn('email',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
        array(
            'nullable'=>false,
        ),'email'
        )
        ->addColumn('mobile',
        Varien_Db_Ddl_Table::TYPE_NUMERIC,null,
        array(
            'nullable'=>false,
        ),'mobile'
        )
        ->addColumn('status',
        Varien_Db_Ddl_Table::TYPE_TINYINT,null,
        array(
            'nullable'=>false,
        ),'status'
        )
        ->addColumn('created_date',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
        array(
            'nullable'=>false,
        ),'createdDate'
    );


$installer->getConnection()->createTable($table);
$installer->endSetup();
    
?>