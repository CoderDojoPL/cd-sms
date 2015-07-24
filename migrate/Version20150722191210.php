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

		$actionLogEntity=new \Entity\LogAction(1);
		$actionLogEntity->setName('Edit user.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(2);
		$actionLogEntity->setName('Add location.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(3);
		$actionLogEntity->setName('Edit location.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(4);
		$actionLogEntity->setName('Remove location.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(5);
		$actionLogEntity->setName('Sign in.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(6);
		$actionLogEntity->setName('Sign out.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(7);
		$actionLogEntity->setName('Add device.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(8);
		$actionLogEntity->setName('Edit device.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(9);
		$actionLogEntity->setName('Remove device.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(10);
		$actionLogEntity->setName('Add order.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(11);
		$actionLogEntity->setName('Featch order.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(12);
		$actionLogEntity->setName('Close order.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(13);
		$actionLogEntity->setName('Set location user.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(14);
		$actionLogEntity->setName('Install system.');
		$this->persist($actionLogEntity);
		$this->flush();

		$logEntity=new \Entity\Log();
		$logEntity->setAction($container->cast('Mapper\LogAction',14));
		$logEntity->setIpAddress('127.0.0.1');
		$logEntity->setIsSuccess(true);
		$this->persist($logEntity);
		$count=0;
		foreach($container->find('User') as $dbEntity){
			$logDbEntity=$this->createLogEntity($dbEntity);
			$logDbEntity->setLogLeft($logEntity);
			$this->persist($logDbEntity);
			$count++;
		}

		foreach($container->find('Order') as $dbEntity){
			$logDbEntity=$this->createLogEntity($dbEntity);
			$logDbEntity->setLogLeft($logEntity);
			$this->persist($logDbEntity);
			$count++;
		}

		foreach($container->find('Location') as $dbEntity){
			$logDbEntity=$this->createLogEntity($dbEntity);
			$logDbEntity->setLogLeft($logEntity);
			$this->persist($logDbEntity);
			$count++;
		}

		foreach($container->find('Device') as $dbEntity){
			$logDbEntity=$this->createLogEntity($dbEntity);
			$logDbEntity->setLogLeft($logEntity);
			$this->persist($logDbEntity);
			$count++;
		}

		$logEntity->setCountModifiedEntities($count);

		$this->flush();
		$this->commitTransaction();

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

		$schema->dropTable('log_actions');
		$schema->dropTable('logs');
		$schema->dropTable('location_logs');
		$schema->dropTable('user_logs');
		$schema->dropTable('device_logs');
		$schema->dropTable('order_logs');
		$schema->dropTable('device_tag_logs');

				
		$this->updateSchema($schema);
		$this->commitTransaction();


	}

	private function createLogEntity($entity){
		$values=array();
		$logEntityName=str_replace('DoctrineProxies\__CG__\\','',get_class($entity)."Log");
		$logEntity=new $logEntityName();
		
		$this->fillLogEntity($entity,$logEntity);
		return $logEntity;
	}

	private function fillLogEntity($entity,&$logEntity){
		$values=array();
		foreach(get_class_methods($entity) as $method){
			if(preg_match('/^get(.*)$/',$method,$finds)){
				$methodName=$finds[1];
				$data=$entity->$method();

				$setMethodName='set'.$methodName;
				if(method_exists($logEntity, $setMethodName)){
					$logEntity->$setMethodName($data);
				}
			}
		}

		return $logEntity;
	}

}