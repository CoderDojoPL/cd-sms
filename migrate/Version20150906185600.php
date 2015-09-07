<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Migrate;

use Arbor\Core\Container;
use Common\MigrateHelper;

/**
 * @package Migrate
 * @author Slawek Mowak (s.nowak@coderdojo.org.pl)
 */
class Version20150906185600 extends MigrateHelper
{

    /**
     * {@inheritdoc}
     */
    public function update(Container $container)
    {

        $this->beginTransaction();
        $schema = $this->createSchema();

        $deviceTypeLogs=$schema->createTable('device_type_logs');
        $deviceTypeLogs->addColumn('id','integer');
        $deviceTypeLogs->addColumn('name','string');
        $deviceTypeLogs->addColumn('symbol_prefix','string',array('default' => '?'));
        $deviceTypeLogs->addColumn('current','integer',array('default' => 0));
        $deviceTypeLogs->addColumn('created_at', 'datetime');
        $deviceTypeLogs->addColumn('log_left_id', 'integer');
        $deviceTypeLogs->addColumn('log_right_id', 'integer', array('notnull' => false));
        $deviceTypeLogs->addColumn('removed', 'boolean');
        $deviceTypeLogs->setPrimaryKey(array('id', 'log_left_id'));

        $logs = $schema->getTable('logs');
        $deviceTypeLogs->addNamedForeignKeyConstraint('FK_1C6017C5DAA1F695', $logs, array('log_left_id'), array('id'));
        $deviceTypeLogs->addNamedForeignKeyConstraint('FK_1C6017C53AC4A3EA', $logs, array('log_right_id'), array('id'));

        $device = $schema->getTable('devices');
        $device->addColumn('symbol', 'string', array('default' => '?'));

        $devicelogs = $schema->getTable('device_logs');
        $devicelogs->addColumn('symbol', 'string', array('default' => '?'));

        $deviceTypeLogs = $schema->getTable('device_types');
        $deviceTypeLogs->addColumn('symbol_prefix', 'string', array('default' => '?'));
        $deviceTypeLogs->addColumn('current', 'integer', array('default' => 0));

        $this->updateSchema($schema);

        $recs = $this->executeQuery("select * FROM devices WHERE type_id = 1");
        $cnt = 0;
        foreach ($recs as $record){
            $this->executeQuery("UPDATE devices set symbol = 'REF".++$cnt."' where id = ".$record['id']);
            $this->executeQuery("UPDATE device_logs set symbol = 'REF".$cnt."' where id = ".$record['id']);
        }
        $this->executeQuery("UPDATE device_types SET symbol_prefix='REF', current=".$cnt." where id = 1");

        $recs = $this->executeQuery("select * FROM devices WHERE type_id = 2");
        $cnt = 0;
        foreach ($recs as $record){
            $this->executeQuery("UPDATE devices set symbol = 'HAR".++$cnt."' where id = ".$record['id']);
            $this->executeQuery("UPDATE device_logs set symbol = 'HAR".$cnt."' where id = ".$record['id']);
        }
        $this->executeQuery("UPDATE device_types SET symbol_prefix='HAR', current=".$cnt." where id = 2");

        $logData=$this->executeQuery("SELECT * FROM logs ORDER BY id LIMIT 1");
        $logId=$logData[0]['id'];
        $count=0;
        foreach($this->getExistedRecords('device_types') as $record){
            $this->createLogRecord('device_type_logs',$record,$logId);
            $count++;
        }

        $this->commitTransaction();

    }


    /**
     * {@inheritdoc}
     */
    public function downgrade(Container $container)
    {
        $this->beginTransaction();
        $schema = $this->createSchema();
        $deviceTypes = $schema->getTable('device_types');
        $deviceTypes->dropColumn('symbol_prefix');
        $deviceTypes->dropColumn('current');

        $device = $schema->getTable('devices');
        $device->dropColumn('symbol');

        $devicelogs = $schema->getTable('device_logs');
        $devicelogs->dropColumn('symbol');
        $schema->dropTable('device_type_logs');
        $this->updateSchema($schema);

        $this->commitTransaction();

    }

    private function getExistedRecords($table){
        return $this->executeQuery("SELECT * FROM ".$table);
    }

    private function createLogRecord($tableName, $record, $logId){
        $ignoreColumn=array('created_at','removed','log_right_id','log_left_id','updated_at');
        $sql=array("INSERT INTO ");
        $sql[]=$tableName;
        $sql[]="(";
        $parameters=array();
        $first=true;
        foreach($record as $columnName=>$value){
            if(in_array($columnName,$ignoreColumn)){
                continue;
            }

            if($first){
                $first=false;
            }
            else{
                $sql[]=",";
            }
            $sql[]=$columnName;
        }
        $sql[]=",created_at,removed,log_left_id) values(";
        $first=true;
        foreach($record as $columnName=>$value){
            if(in_array($columnName,$ignoreColumn)){
                continue;
            }

            if($first){
                $first=false;
            }
            else{
                $sql[]=",";
            }
            $sql[]=":";
            $sql[]=$columnName;
            $parameters[$columnName]=$value;
        }
        $sql[]=",now(),false,:logId);";
        $parameters['logId']=$logId;
        $this->executeQuery(implode("",$sql),$parameters);
    }
}