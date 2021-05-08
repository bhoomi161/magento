<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
        ->newTable($installer->getTable('ccc_salesman/data'))
        ->addColumn('salesman_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'=>true,
            'nullable'=>false,
            'primary'=>true
        ),'Id')
        ->addColumn('name',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        null,
        array(
            'nullable'=>false,
        ),'name')
        ->addColumn('email',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        null,
        array(
            'nullable'=>false,
        ),'email')
        ->addColumn('mobile',
        Varien_Db_Ddl_Table::TYPE_NUMERIC,
        null,
        array(
        'nullable'=>false,
        ),'mobile')
        ->addColumn('created_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME,
        null,
        array(
            'nullable'=>false,
        ),'createdDate');

$installer->getConnection()->createTable($table);
$installer->endSetup();

?>