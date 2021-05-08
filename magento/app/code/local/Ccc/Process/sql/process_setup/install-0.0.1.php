<?php

$installer = $this;
$installer->startSetup();

//****ProcessType Table******//

$processTypeTable = $installer->getConnection()->newTable($installer->getTable('process/process_type'))
                    ->addColumn('process_type_id',
                    Varien_Db_Ddl_Table::TYPE_INTEGER,
                    null,
                    array(
                        'identity'=>true,
                        'nullable'=>false,
                        'primary'=>true
                    ),'Process Type Id')
                    ->addColumn('name',
                    Varien_Db_Ddl_Table::TYPE_VARCHAR,
                    null,
                     array(
                     'nullable'=>false,
                    ),'Name');

//$installer->getConnection()->createTable($processTypeTable);

//*****Process group Table ******//

$processGroupTable =  $installer->getConnection()->newTable($installer->getTable('process/process_group'))
                    ->addColumn('group_id',
                    Varien_Db_Ddl_Table::TYPE_INTEGER,
                    null,
                    array(
                        'identity'=>true,
                        'nullable'=>false,
                        'primary'=>true
                    ),'Group Id')
                    ->addColumn('name',
                    Varien_Db_Ddl_Table::TYPE_VARCHAR,
                    null,
                    array(
                    'nullable'=>false,
                    ),'Name');

//$installer->getConnection()->createTable($processGroupTable);

//*****Process Table*****//

$processTable = $installer->getConnection()->newTable($installer->getTable('process/process'))
        ->addColumn('process_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'=>true,
            'nullable'=>false,
            'primary'=>true
        ),'Process Id')
        ->addColumn('process_type_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'=>false,
        ),'Process Type Id')
        ->addColumn('group_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'=>false,
        ),'Group Id')
        ->addColumn('name',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        null,
        array(
            'nullable'=>false,
        ),'Name')
        ->addColumn('per_request_count',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'=>false,
        ),'Per Request Count')
        ->addColumn('request_interval',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'=>false,
        ),'Request Interval')
        ->addColumn('model',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        null,
        array(
            'nullable'=>false,
        ),'Model')
        ->addColumn('filename',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        null,
        array(
            'nullable'=>false,
        ),'Filename')
        ->addColumn('createdDate',
        Varien_Db_Ddl_Table::TYPE_DATETIME,
        null,
        array(
            'nullable'=>false,
        ),'Created Date')
        ->addForeignKey(
            $installer->getFkName(
                'process/process',
                'process_type_id',
                'process/process_type',
                'process_type_id'
            ),
            'process_type_id',
            $installer->getTable('process/process_type'),
            'process_type_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->addForeignKey(
            $installer->getFkName(
                'process/process',
                'group_id',
                'process/process_group',
                'group_id'
            ),
            'group_id',
            $installer->getTable('process/process_group'),
            'group_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        );

//$installer->getConnection()->createTable($processTable);


//******Process Log Table *****/

$processLogTable = $installer->getConnection()->newTable($installer->getTable('process/process_log'))
                    ->addColumn('process_log_id',
                    Varien_Db_Ddl_Table::TYPE_INTEGER,
                    null,
                    array(
                        'identity'=>true,
                        'nullable'=>false,
                        'primary'=>true
                    ),'Process Log Id')
                    ->addColumn('process_id',
                    Varien_Db_Ddl_Table::TYPE_INTEGER,
                    null,
                    array(
                        'nullable'=>false,
                    ),'Process Id')
                    ->addColumn('createdDate',
                    Varien_Db_Ddl_Table::TYPE_DATETIME,
                    null,
                    array(
                        'nullable'=>false,
                    ),'Created Date')
                    ->addForeignKey(
                        $installer->getFkName(
                        'process/process_log', 'process_id', 
                        'process/process', 'process_id'),
                        'process_id',
                        $installer->getTable('process/process'),
                        'process_id',
                        Varien_Db_Ddl_Table::ACTION_CASCADE,
                        Varien_Db_Ddl_Table::ACTION_CASCADE
                    );
                    

$installer->getConnection()->createTable($processLogTable);

//***** ProcessTypeColumn Table******//

$processTypeColumnTable = $installer->getConnection()->newTable($installer->getTable('process/process_type_column'))
                            ->addColumn('column_id',
                            Varien_Db_Ddl_Table::TYPE_INTEGER,
                            null,
                            array(
                                'identity'=>true,
                                'nullable'=>false,
                                'primary'=>true
                            ),'Column Id')
                            ->addColumn('process_id',
                            Varien_Db_Ddl_Table::TYPE_INTEGER,
                            null,
                            array(
                                'nullable'=>false,
                            ),'Process Id')
                            ->addColumn('name',
                            Varien_Db_Ddl_Table::TYPE_VARCHAR,
                            null,
                            array(
                                'nullable'=>false,
                            ),'Name')
                            ->addColumn('sample',
                            Varien_Db_Ddl_Table::TYPE_VARCHAR,
                            null,
                            array(
                                'nullable'=>false,
                            ),'Sample')
                            ->addColumn('default',
                            Varien_Db_Ddl_Table::TYPE_VARCHAR,
                            null,
                            array(
                                'nullable'=>false,
                            ),'Default')
                            ->addColumn('is_required',
                            Varien_Db_Ddl_Table::TYPE_BOOLEAN,
                            null,
                            array(
                                'nullable'=>false,
                            ),'Is Required')
                            ->addColumn('backend_type',
                            Varien_Db_Ddl_Table::TYPE_VARCHAR,
                            null,
                            array(
                                'nullable'=>false,
                            ),'Backend Type')
                            ->addColumn('sort_order',
                            Varien_Db_Ddl_Table::TYPE_INTEGER,
                            null,
                            array(
                                'nullable'=>false,
                            ),'Sort Order')
                            ->addForeignKey(
                                $installer->getFkName(
                                    'process/process_type_column', 'process_id',
                                    'process/process', 'process_id'),
                                'process_id',
                                $installer->getTable('process/process'),
                                'process_id',
                                Varien_Db_Ddl_Table::ACTION_CASCADE,
                                Varien_Db_Ddl_Table::ACTION_CASCADE
                            );
                            

$installer->getConnection()->createTable($processTypeColumnTable);

////****ProcessLogIdx Table ******//

$processLogIdxTable =  $installer->getConnection()->newTable($installer->getTable('process/process_log_idx'))
                        ->addColumn('entity_id',
                        Varien_Db_Ddl_Table::TYPE_INTEGER,
                        null,
                        array(
                            'identity'=>true,
                            'nullable'=>false,
                            'primary'=>true
                        ),'Entity Id')
                        ->addColumn('process_id',
                        Varien_Db_Ddl_Table::TYPE_INTEGER,
                        null,
                        array(
                            'nullable'=>false,
                        ),'Process Id')
                        ->addColumn('process_log_id',
                        Varien_Db_Ddl_Table::TYPE_INTEGER,
                        null,
                        array(
                            'nullable'=>false,
                        ),'Process Log Id')
                        ->addColumn('unique_id',
                        Varien_Db_Ddl_Table::TYPE_INTEGER,
                        null,
                        array(
                            'nullable'=>false,
                        ),'Unique Id')
                        ->addColumn('data',
                        Varien_Db_Ddl_Table::TYPE_TEXT,
                        null,
                        array(
                            'nullable'=>false,
                        ),'data')
                        ->addColumn('start_time',
                        Varien_Db_Ddl_Table::TYPE_DATETIME,
                        null,
                        array(
                            'nullable'=>false,
                        ),'StartTime')
                        ->addColumn('end_time',
                        Varien_Db_Ddl_Table::TYPE_DATETIME,
                        null,
                        array(
                            'nullable'=>false,
                        ),'EndTime')
                        ->addForeignKey(
                            $installer->getFkName(
                            'process/process_log_idx', 'process_log_id', 
                            'process/process_log', 'process_log_id'),
                            'process_log_id',
                            $installer->getTable('process/process_log'),
                            'process_log_id',
                            Varien_Db_Ddl_Table::ACTION_CASCADE,
                            Varien_Db_Ddl_Table::ACTION_CASCADE
                        )
                        ->addForeignKey(
                            $installer->getFkName(
                            'process/process_log_idx', 'process_id', 
                            'process/process', 'process_id'),
                            'process_id',
                            $installer->getTable('process/process'),
                            'process_id',
                            Varien_Db_Ddl_Table::ACTION_CASCADE,
                            Varien_Db_Ddl_Table::ACTION_CASCADE
                        );
                        
                        
$installer->getConnection()->createTable($processLogIdxTable);

$installer->endSetup();

?>