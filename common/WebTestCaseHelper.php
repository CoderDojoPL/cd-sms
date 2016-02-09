<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common;
require_once __DIR__.'/../arbor/core/WebTestCase.php';

use Arbor\Core\WebTestCase;
use Entity\User;
use Entity\Role;
use Arbor\Exception\ElementNotFoundException;

/**
 * Test helper.
 *
 * @package Test
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class WebTestCaseHelper extends WebTestCase{	

	/**
	 * Entity\User
	 *
	 * @var \Entity\User $user
	 */
	protected $user;

	/**
	 * Entity\Log
	 *
	 * @var \Entity\Log $log
	 */
	protected $log;

	/**
	 * Configure enviorment.
	 */
	protected function setUp(){
		$res=$this->executeCommand('migrate:downgrade');
		if($res[0]!=0){
			throw new \Exception('Migrate - '.$res[1]);
		}
		$res=$this->executeCommand('migrate:update');
		if($res[0]!=0){
			throw new \Exception('Migrate - '.$res[1]);
		}

		$em=$this->getService('doctrine')->getEntityManager();

		$this->log=new \Entity\Log();
		$this->log->setAction($this->getService('doctrine')->getRepository('Entity\LogAction')->findOneById(14));
		$this->log->setIpAddress('127.0.0.1');
		$this->log->setIsSuccess(true);
		$this->log->setCountModifiedEntities(0);
		$em->persist($this->log);

		$role=new Role();
		$role->setName('Admin');
		foreach($em->getRepository('Entity\Functionality')->findAll() as $functionality){
			$role->getFunctionalities()->add($functionality);
		}

		$this->persist($role);
		$user=new User();
		$user->setEmail('test@coderdojo.org.pl');
		$user->setFirstName('first name');
		$user->setLastName('last name');
		$user->setLocation($em->getRepository('Entity\Location')->findOneBy(array()));
		$user->setRole($role);
		$em->persist($user);
		$this->createLogEntity($user);
		$em->flush();
		$this->user=$user;

	}

	/** 
	 * Persist entity and create log
	 *
	 * @param object $entity
	 */
	protected function persist($entity){
		$em=$this->getService('doctrine')->getEntityManager();
		$em->persist($entity);
		$this->createLogEntity($entity);

	}

	/** 
	 * Save entites changes
	 */
	protected function flush(){
		$em=$this->getService('doctrine')->getEntityManager();
		$em->flush();

	}

	/**
	 * Create log entity
	 *
	 * @param object $entity entity object
	 */
	protected function createLogEntity($entity){
		$logEntityName=get_class($entity).'Log';
		$logEntity=new $logEntityName();
		$this->fillLogEntity($entity,$logEntity);
		$logEntity->setLogLeft($this->log);
		$em=$this->getService('doctrine')->getEntityManager();
		$em->persist($logEntity);
	}

	/**
	 * Forward data form entity to log entity
	 *
	 * @param object $entity basic entity
	 * @param object $logEntity log entity
	 */
	protected function fillLogEntity($entity,&$logEntity){
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

	/**
	 * Assert each field for validate labels
	 *
	 * @param array $fields
	 * @param array $config list with error message
	 */
	protected function assertFields($fields,$config){
		foreach($config as $fName=>$fMessage){
			if(!isset($fields[$fName])){
				$this->assertTrue(false,'Field '.$fName.' not found.');
			}
			$field=$fields[$fName];

			if($fMessage===null){
		        $this->assertFalse($field->getParent()->hasElement('label'), 'Redundant error message for '.$fName);
			}
			else{
		        $this->assertTrue($field->getParent()->hasElement('label'), 'Field '.$fName.' is not required.');
		        $this->assertEquals($fMessage, $field->getParent()->getElement('label')->getText(), 'Invalid error message for '.$fName);
			}
		}

        $this->assertCount(count($config), $fields, 'Invalid number fields');

	}

	/**
	 * Check url and status code
	 *
	 * @param Arbor\Test\BrowserEmulator $client
	 * @param string $url
	 */
	protected function assertUrl($client,$url){
		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code for url '.$client->getUrl().'. Content'.$client->getResponse()->getContent());
        $this->assertEquals($url, $client->getUrl(), 'Invalid url for '.$client->getUrl());
	}

	/**
	 * Assert each field by data
	 *
	 * @param array $fields
	 * @param array $config list with error message
	 */
	protected function assertFieldsData($fields,$config){
		foreach($config as $fName=>$fMessage){
			if(!isset($fields[$fName])){
				$this->assertTrue(false,'Field '.$fName.' not found.');
			}
			$field=$fields[$fName];

	        $this->assertEquals($fMessage,$field->getData(), 'Invalid data for field '.$fName);
		}

        $this->assertCount(count($config), $fields, 'Invalid number fields');

	}

}
