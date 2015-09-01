<?php

namespace Event;
use Arbor\Core\Event;
use Arbor\Provider\Response;
use Arbor\Event\ExecutePresenterEvent;
use Exception\UserNotFoundException;
use Exception\LogEntityNotFoundException;
use Arbor\Exception\ValueNotFoundException;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Exception\LogActionNotFoundException;
use Doctrine\ORM\ORMException;
use Arbor\Event\ExecuteActionEvent;
use Exception\LogNotFoundException;
use Arbor\Exception\HeaderNotFoundException;

class Log extends Event{

	private $log;
	private $monitoring=false;
	private $insertedEntities=array();
	private $updatedEntities=array();
	private $removedEntities=array();
	private $entityCount=0;
	private $reconnectManager=false;
	private $flushedEntities=array();

	public function onExecuteAction(ExecuteActionEvent $event){
		$request=$event->getRequest();

		$logAction=$this->getLogAction($request);
		if($logAction){
			$this->createLog($event,$logAction);
			$this->insertedEntities=array();
			$this->updatedEntities=array();
			$this->removedEntities=array();

		}

		$this->monitoring=true;
	}

	public function onExecutePresenter(ExecutePresenterEvent $event){
		$this->monitoring=false;
		if($this->log){
			try{
				$em=$this->getService('doctrine')->getEntityManager();
				$em->clear();
				$this->closeLogRecord($event,$em);
			}
			catch(\Exception $e){
				if(!$em->isOpen() && !$this->reconnectManager){
					$em=$em->create($em->getConnection(),$em->getConfiguration());

					$this->reconnectManager=true;
					try{
						$this->closeLogRecord($event,$em);
					}
					catch(\Exception $e){
						error_log('Invalid log save: '.$e->getMessage());
					}
				}
				else{
					error_log('Invalid log save: '.$e->getMessage());
				}
			}
		}
	}

	private function closeLogRecord($event,$em){
		$responseContent=$event->getResponse()->getContent();
		$isSuccess=true;
		if($event->getResponse()->getStatusCode()>=400)
			$isSuccess=false;
		$this->log=$em->getRepository('Entity\Log')->findOneBy(array('id'=>$this->log->getId()));

		$this->log->setCountModifiedEntities($this->entityCount);
		$this->log->setIsSuccess($isSuccess);
		$this->log->setResult(json_encode($event->getResponse()->getContent()));

		$this->log->setUser($this->getSessionUser($event));

		if(!$isSuccess && $responseContent instanceof \Exception)
			$this->log->setFailMessage($responseContent->getMessage());
			$em->persist($this->log);

		$em->flush();

	}

	private function createLog($event,$logAction){
		$em=$this->getDoctrine()->getEntityManager();
		$request=$event->getRequest();


		$log=new \Entity\Log();
		$log->setAction($logAction);
		$log->setUser($this->getSessionUser($event));
		$log->setIsSuccess(false);
		$log->setArguments(json_encode($this->filterArguments($request->getArguments())));
		$log->setIpAddress($request->getClientIp());
		$log->setCountModifiedEntities(0);
		try{
			$log->setUserAgent($request->getHeader('user-agent'));
		}
		catch(HeaderNotFoundException $e){
			//ignore - nie został wysłany nagłówek
		}

		$em->persist($log);
		$em->flush();

		$this->log=$log;
	}

	private function getLogAction($request,$reconnect=true){
		$actionId=0;
		foreach($request->getExtra() as $extra){
			foreach($extra as $parameter=>$config){
				if($parameter=='log'){
					$type=(isset($config['type'])?strtoupper($config['type']):'POST');
					if($request->getType()==$type){
						$actionId=$config['action'];
						break;
					}

				}
			}
		}

		if(!$actionId){
			return null;
		}

		return $this->findOne('LogAction',array(
			'id'=>$actionId
		));


	}

	public function onFlush(OnFlushEventArgs $eventArgs){
		if(!$this->monitoring)
			return;

		$em=$eventArgs->getEntityManager();
		$this->conn=$em->getConnection();
		$this->conn->beginTransaction();

		$uow = $em->getUnitOfWork();

        // Insertions
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
	    		$this->insertedEntities[]=$entity;
        }

        //Updates
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
	    		$this->updatedEntities[]=$entity;
        }

       //  //Deletions
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
	    		$this->removedEntities[]=array('id'=>$entity->getId(),'name'=>str_replace('DoctrineProxies\__CG__\\','',get_class($entity)."Log"));

        }
	}

	public function postFlush(PostFlushEventArgs $eventArgs){
		if(!$this->monitoring)
			return;
		$this->monitoring=false;

		$em=$eventArgs->getEntityManager();
		if(!$this->log){
			$this->conn->rollback();
			throw new LogNotFoundException();			
		}

		$log=$this->log;

		foreach($this->insertedEntities as $entity){

			$logEntity=$this->createLogEntity($entity);


			$logEntity->setLogLeft($log);
			$em->persist($logEntity);
			$this->entityCount++;
			if(!in_array($entity, $this->flushedEntities)){
				$this->flushedEntities[]=$entity;
			}
		}

		foreach($this->updatedEntities as $entity){
			$logEntity=$this->getUpdatedLogEntity($entity->getId(),str_replace('DoctrineProxies\__CG__\\','',get_class($entity)."Log"));
			if(in_array($entity, $this->flushedEntities)){ //modified record in that same session
				$this->fillLogEntity($entity,$logEntity);
			}
			else{ //edited record
				$logEntity->setLogRight($log);

				$updatedLogEntity=$this->createLogEntity($entity);
				$updatedLogEntity->setLogLeft($log);
				$em->persist($updatedLogEntity);
				$this->entityCount++;
				$this->flushedEntities[]=$entity;

			}
		}
		foreach($this->removedEntities as $entity){
			$logEntity=$this->getUpdatedLogEntity($entity['id'],$entity['name']);
			$logEntity->setLogRight($log);
			$logEntity->setRemoved(true);
			$this->entityCount++;
		}

		$em->flush();

		$this->conn->commit();

		$this->monitoring=true;

		$this->insertedEntities =
		$this->updatedEntities =
		$this->removedEntities=array();

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


	private function filterArguments($arguments){
		$data=array();
		foreach($arguments as $kArgument=>$argument){
			if($kArgument=='password')
				$argument='...';

			if(is_object($argument)){
				if(is_callable(array($argument,'getId')))
					$data[$kArgument]=$argument->getId();
				else if($argument instanceof \Arbor\Collection\ArrayList)
					$data[$kArgument]=json_decode((string)$argument,true);
				else
					$data[$kArgument]=get_class($argument);

			}
			else
				$data[$kArgument]=$argument;

		}

		return $data;
	}

	private function getUpdatedLogEntity($id,$name){
		$em=$this->getService('doctrine')->getEntityManager();
		$entity=$em->getRepository($name)->findOneBy(array('id'=>$id,'logRight'=>null));

		if(!$entity)
			throw new LogEntityNotFoundException();

		return $entity;

	}

	private function getSessionUser($event){
		try{
			$userId=$event->getRequest()->getSession()->get('user.id');

			$doctrine=$this->getService('doctrine');
			$user=$doctrine->getRepository('Entity\User')->findOneById($userId);

			if(!$user)
				throw new UserNotFoundException();

			return $user;
		}
		catch(ValueNotFoundException $e){
			return null;
		}

	}

}