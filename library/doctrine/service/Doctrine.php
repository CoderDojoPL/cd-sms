<?php

namespace Library\Doctrine\Service;

use Library\Doctrine\EventHandler;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Arbor\Contener\ServiceConfig;
use Doctrine\DBAL\Event\Listeners\MysqlSessionInit;
use Arbor\Core\EventManager;

require_once "../library/doctrine/autoloader.php";


class Doctrine{
	
	private $entityManager;

	public function __construct(ServiceConfig $serviceConfig,EventManager $eventManager){

		$paths = array("../src/entity");
		$isDevMode = true;
		$eventHandler=new EventHandler($eventManager);

		// the connection configuration
		$dbParams = array(
		    'driver'   => $serviceConfig->get('driver'),
		    'user'     => $serviceConfig->get('user'),
		    'password' => $serviceConfig->get('password'),
		    'dbname'   => $serviceConfig->get('dbname'),
		    'host'   => $serviceConfig->get('host'),
	        'charset' => 'utf8'

		);

		$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
		$this->entityManager = EntityManager::create($dbParams, $config);

		$this->entityManager->getEventManager()->addEventListener(array('onFlush'), $eventHandler);
	}

	public function getEntityManager(){
		return $this->entityManager;
	}

	public function getRepository($class){
		return $this->getEntityManager()->getRepository($class);
	}
}