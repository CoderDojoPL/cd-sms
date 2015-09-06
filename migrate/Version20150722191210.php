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
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Version20150722191210 extends MigrateHelper{

	/**
	 * {@inheritdoc}
	 */
	public function update(Container $container){

		$this->beginTransaction();
		$schema=$this->createSchema();
		$logActions=$schema->createTable('log_actions');
		$users=$schema->getTable('users');

		$logs=$schema->createTable('logs');
		$deviceTypes=$schema->getTable('device_types');
		$deviceStates=$schema->getTable('device_states');
		$orderStates=$schema->getTable('order_states');

		$logs->addColumn('id','integer',array('autoincrement'=>true));
		$logs->addColumn('user_id','integer',array('notnull'=>false));
		$logs->addColumn('log_action_id','integer');
		$logs->addColumn('arguments','text',array('notnull'=>false));
		$logs->addColumn('result','text',array('notnull'=>false));
		$logs->addColumn('ip_address','text');
		$logs->addColumn('user_agent','text',array('notnull'=>false));
		$logs->addColumn('is_success','boolean');
		$logs->addColumn('count_modified_entities','integer');
		$logs->addColumn('fail_message','text',array('notnull'=>false));
		$logs->addColumn('created_at','datetime');
		$logs->setPrimaryKey(array('id'));


		$locationLogs=$schema->createTable('location_logs');
		$locationLogs->addColumn('id','integer');
		$locationLogs->addColumn('name','string');
		$locationLogs->addColumn('city','string');
		$locationLogs->addColumn('street','string');
		$locationLogs->addColumn('number','string');
		$locationLogs->addColumn('apartment','string',array('notnull'=>false));
		$locationLogs->addColumn('postal','string');
		$locationLogs->addColumn('phone','string');
		$locationLogs->addColumn('email','string');
		$locationLogs->addColumn('created_at','datetime');
		$locationLogs->addColumn('log_left_id','integer');
		$locationLogs->addColumn('log_right_id','integer',array('notnull'=>false));
		$locationLogs->addColumn('removed','boolean');
		$locationLogs->setPrimaryKey(array('id','log_left_id'));

		$userLogs=$schema->createTable('user_logs');
		$userLogs->addColumn('id','integer');
		$userLogs->addColumn('location_id','integer',array('notnull'=>false));
		$userLogs->addColumn('email','string');
		$userLogs->addColumn('first_name','string');
		$userLogs->addColumn('last_name','string');
		$userLogs->addColumn('created_at','datetime');
		$userLogs->addColumn('log_left_id','integer');
		$userLogs->addColumn('log_right_id','integer',array('notnull'=>false));
		$userLogs->addColumn('removed','boolean');
		$userLogs->setPrimaryKey(array('id','log_left_id'));

		$deviceLogs=$schema->createTable('device_logs');
		$deviceLogs->addColumn('id','integer');
		$deviceLogs->addColumn('type_id','integer',array('notnull'=>false));
		$deviceLogs->addColumn('state_id','integer');
		$deviceLogs->addColumn('location_id','integer');
		$deviceLogs->addColumn('name','string');
		$deviceLogs->addColumn('photo','string',array('notnull'=>false));
		$deviceLogs->addColumn('dimensions','string');
		$deviceLogs->addColumn('weight','string');
		$deviceLogs->addColumn('serial_number','string');
		$deviceLogs->addColumn('warranty_expiration_date','datetime',array('notnull'=>false));
		$deviceLogs->addColumn('price','decimal',array('scale'=>2,'notnull'=>false));
		$deviceLogs->addColumn('note','text',array('notnull'=>false));
		$deviceLogs->addColumn('created_at','datetime');
		$deviceLogs->addColumn('log_left_id','integer');
		$deviceLogs->addColumn('log_right_id','integer',array('notnull'=>false));
		$deviceLogs->addColumn('removed','boolean');
		$deviceLogs->setPrimaryKey(array('id','log_left_id'));


		$orderLogs=$schema->createTable('order_logs');
		$orderLogs->addColumn('id','integer');
		$orderLogs->addColumn('owner_id','integer');
		$orderLogs->addColumn('device_id','integer');
		$orderLogs->addColumn('state_id','integer');
		$orderLogs->addColumn('performer_id','integer',array('notnull'=>false));
		$orderLogs->addColumn('fetched_at','datetime',array('notnull'=>false));
		$orderLogs->addColumn('closed_at','datetime',array('notnull'=>false));
		$orderLogs->addColumn('created_at','datetime');
		$orderLogs->addColumn('log_left_id','integer');
		$orderLogs->addColumn('log_right_id','integer',array('notnull'=>false));
		$orderLogs->addColumn('removed','boolean');
		$orderLogs->setPrimaryKey(array('id','log_left_id'));

		$deviceTagLogs=$schema->createTable('device_tag_logs');
		$deviceTagLogs->addColumn('id','integer');
		$deviceTagLogs->addColumn('name','string');
		$deviceTagLogs->addColumn('created_at','datetime');
		$deviceTagLogs->addColumn('log_left_id','integer');
		$deviceTagLogs->addColumn('log_right_id','integer',array('notnull'=>false));
		$deviceTagLogs->addColumn('removed','boolean');
		$deviceTagLogs->setPrimaryKey(array('id','log_left_id'));

		$logActions->addColumn('id','integer');
		$logActions->addColumn('name','string');
		$logActions->setPrimaryKey(array('id'));


		$logs->addNamedForeignKeyConstraint('FK_F08FC65CA76ED395',$users, array('user_id'),array('id'));
		$logs->addNamedForeignKeyConstraint('FK_F08FC65C8B306CBB',$logActions, array('log_action_id'),array('id'));


		$locationLogs->addNamedForeignKeyConstraint('FK_708B731CDAA1F695',$logs, array('log_left_id'),array('id'));
		$locationLogs->addNamedForeignKeyConstraint('FK_708B731C3AC4A3EA',$logs, array('log_right_id'),array('id'));

		$userLogs->addNamedForeignKeyConstraint('FK_8A0E8A95DAA1F695',$logs, array('log_left_id'),array('id'));
		$userLogs->addNamedForeignKeyConstraint('FK_8A0E8A953AC4A3EA',$logs, array('log_right_id'),array('id'));

		$deviceLogs->addNamedForeignKeyConstraint('FK_79B61366C54C8C93',$deviceTypes, array('type_id'),array('id'));
		$deviceLogs->addNamedForeignKeyConstraint('FK_79B613665D83CC1',$deviceStates, array('state_id'),array('id'));
		$deviceLogs->addNamedForeignKeyConstraint('FK_79B61366DAA1F695',$logs, array('log_left_id'),array('id'));
		$deviceLogs->addNamedForeignKeyConstraint('FK_79B613663AC4A3EA',$logs, array('log_right_id'),array('id'));


		$orderLogs->addNamedForeignKeyConstraint('FK_BD7EFC4B5D83CC1',$orderStates, array('state_id'),array('id'));
		$orderLogs->addNamedForeignKeyConstraint('FK_BD7EFC4BDAA1F695',$logs, array('log_left_id'),array('id'));
		$orderLogs->addNamedForeignKeyConstraint('FK_BD7EFC4B3AC4A3EA',$logs, array('log_right_id'),array('id'));


		$deviceTagLogs->addNamedForeignKeyConstraint('FK_90B420CADAA1F695',$logs, array('log_left_id'),array('id'));
		$deviceTagLogs->addNamedForeignKeyConstraint('FK_90B420CA3AC4A3EA',$logs, array('log_right_id'),array('id'));


		$this->updateSchema($schema);

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>1
		,'name'=>'Edit user.'
		));

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>2
		,'name'=>'Add location.'
		));

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>3
		,'name'=>'Edit location.'
		));

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>4
		,'name'=>'Remove location.'
		));

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>5
		,'name'=>'Sign in.'
		));


		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>6
		,'name'=>'Sign out.'
		));

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>7
		,'name'=>'Add device.'
		));


		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>8
		,'name'=>'Edit device.'
		));

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>9
		,'name'=>'Remove device.'
		));

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>10
		,'name'=>'Add order.'
		));

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>11
		,'name'=>'Fetch order.'
		));

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>12
		,'name'=>'Close order.'
		));


		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>13
		,'name'=>'Set location user.'
		));

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>14
		,'name'=>'Install system.'
		));


		$this->executeQuery("INSERT INTO logs(".($this->getDriver()=='pdo_pgsql'?'id,':'')."log_action_id,ip_address,is_success,count_modified_entities,created_at) VALUES(".($this->getDriver()=='pdo_pgsql'?"nextval('logs_id_seq'),":'').":logActionId,:ipAddress,:isSuccess,0,now())",array(
			'logActionId'=>'14'
		,'ipAddress'=>'127.0.0.1'
		,'isSuccess'=>true
		));

		$logData=$this->executeQuery("SELECT * FROM logs ORDER BY id DESC LIMIT 1");
		$logId=$logData[0]['id'];
		$count=0;
		foreach($this->getExistedRecords('users') as $record){
			$this->createLogRecord('user_logs',$record,$logId);
			$count++;
		}

		foreach($this->getExistedRecords('orders') as $record){
			$this->createLogRecord('order_logs',$record,$logId);
			$count++;
		}

		foreach($this->getExistedRecords('locations') as $record){
			$this->createLogRecord('location_logs',$record,$logId);
			$count++;
		}

		foreach($this->getExistedRecords('devices') as $record){
			$this->createLogRecord('device_logs',$record,$logId);
			$count++;
		}

		$this->executeQuery("UPDATE logs SET count_modified_entities=:count WHERE id=:id",array(
			'id'=>$logId
		,'count'=>$count
		));

		$this->commitTransaction();

	}

	private function getExistedRecords($table){
		return $this->executeQuery("SELECT * FROM ".$table);
	}

	/**
	 * {@inheritdoc}
	 */
	public function downgrade(Container $container){
		$this->beginTransaction();
		$schema=$this->createSchema();

		$logs=$schema->getTable('logs');
		$locationLogs=$schema->getTable('location_logs');
		$userLogs=$schema->getTable('user_logs');
		$deviceLogs=$schema->getTable('device_logs');
		$orderLogs=$schema->getTable('order_logs');
		$deviceTagLogs=$schema->getTable('device_tag_logs');

		$logs->removeForeignKey('FK_F08FC65CA76ED395');
		$logs->removeForeignKey('FK_F08FC65C8B306CBB');


		$locationLogs->removeForeignKey('FK_708B731CDAA1F695');
		$locationLogs->removeForeignKey('FK_708B731C3AC4A3EA');

		$userLogs->removeForeignKey('FK_8A0E8A95DAA1F695');
		$userLogs->removeForeignKey('FK_8A0E8A953AC4A3EA');

		$deviceLogs->removeForeignKey('FK_79B61366C54C8C93');
		$deviceLogs->removeForeignKey('FK_79B613665D83CC1');
		$deviceLogs->removeForeignKey('FK_79B61366DAA1F695');
		$deviceLogs->removeForeignKey('FK_79B613663AC4A3EA');

		$orderLogs->removeForeignKey('FK_BD7EFC4B5D83CC1');
		//$orderLogs->removeForeignKey('FK_BD7EFC4BDAA1F695');
		// $orderLogs->removeForeignKey('FK_BD7EFC4B3AC4A3EA');

		// $deviceTagLogs->removeForeignKey('FK_90B420CADAA1F695');
		// $deviceTagLogs->removeForeignKey('FK_90B420CA3AC4A3EA');

		$schema->dropTable('location_logs');
		$schema->dropTable('user_logs');
		$schema->dropTable('device_logs');
		$schema->dropTable('order_logs');
		$schema->dropTable('device_tag_logs');
		$schema->dropTable('logs');
		$schema->dropTable('log_actions');


		$this->updateSchema($schema);
		$this->commitTransaction();


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