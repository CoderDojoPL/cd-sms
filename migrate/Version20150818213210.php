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
class Version20150818213210 extends MigrateHelper{
	
	/**
	 * {@inheritdoc}
	 */
	public function update(Container $container){

		$this->beginTransaction();
		$schema=$this->createSchema();
		$deviceLogs=$schema->getTable('device_logs');
		$devices=$schema->getTable('devices');
		$users=$schema->getTable('users');

		$devices->addColumn('user_id','integer',array('notnull'=>false));

		$devices->addNamedForeignKeyConstraint('FK_11074E9AA76ED395',$users, array('user_id'),array('id'),array('onDelete'=>'CASCADE'));

		$deviceLogs->addColumn('user_id','integer',array('notnull'=>false));


		$this->updateSchema($schema);

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
		$deviceLogs=$schema->getTable('device_logs');
		$devices=$schema->getTable('devices');

		// $devices->removeForeignKey('fk_11074e9aa76ed395');
		$devices->dropColumn('user_id');

		$deviceLogs->dropColumn('user_id');

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