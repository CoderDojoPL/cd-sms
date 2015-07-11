<?php
/**
* @Version 0.17.0
*/
namespace Arbor;

require '../arbor/core/Autoloader.php';

use Arbor\Core\Autoloader;
use Arbor\Contener\GlobalConfig;
use Arbor\Contener\RequestConfig;
use Arbor\Contener\ServiceConfig;
use Arbor\Contener\CommandConfig;
use Arbor\Provider\Response;
use Arbor\Core\RequestProvider;
use Arbor\Provider\Request;
use Arbor\Provider\Session;
use Arbor\Core\Presenter;
use Arbor\Core\ErrorHandler;
use Arbor\Core\EventManager;
use Arbor\Event\ExecuteActionEvent;
use Arbor\Event\ExecutedActionEvent;
use Arbor\Event\ExecutePresenterEvent;
use Arbor\Exception\ActionNotFoundException;
use Arbor\Exception\CommandNotFoundException;
use Arbor\Core\ExecuteResources;
use Arbor\Core\Enviorment;
use Arbor\Test\Request as RequestTest;
use Arbor\Core\Router;

class Root{
	
	private $autoloader;
	private $errorHandler;
	private $executeResources;
	private $eventManager;
	private $router;

	public function __construct($debug,$silent,$name){
		$this->autoloader=new Autoloader();
		$enviorment=new Enviorment($debug,$silent,$name);
		$this->executeResources=new ExecuteResources();
		$this->executeResources->registerEnviorment($enviorment);
		$this->router=new Router();
		$this->eventManager=new EventManager($this->executeResources);
		$this->errorHandler=new ErrorHandler($this->executeResources,$this->eventManager);

		$this->executeResources->registerGlobalConfig(new GlobalConfig('../config',$enviorment));
		$this->registerServices($this->executeResources);
		$this->registerEvents($this->executeResources);
		$this->registerSnippets($this->executeResources);
	}


	public function executeCommand($command){
		try{

			$this->executeResources->registerUrl($command[0]);
			array_shift($command);
			$dispatcher=$this->router->createCommandDispatcher($this->executeResources->getEnviorment(),$this->executeResources->getGlobalConfig(),$this->executeResources->getUrl(),$command);
			$dispatcher->execute($this->executeResources,$this->eventManager);

		}
		catch(\Exception $e){
			$this->errorHandler->exception($e);
		}

	}

	public function executeRequest(){
		try{
			$url=strstr($_SERVER['REQUEST_URI'],'?',true);
			if(!$url)
				$url=$_SERVER['REQUEST_URI'];
			$this->executeResources->registerUrl($url);

			$dispatcher=$this->router->createHttpDispatcher($this->executeResources->getEnviorment(),$this->executeResources->getGlobalConfig(),$this->executeResources->getUrl());
			$dispatcher->execute($this->executeResources,$this->eventManager);
		}
		catch(\Exception $e){
			$this->errorHandler->exception($e);
		}

	}

	public function executeRequestTest(RequestTest $request){
		$url=$request->getUrl();
		$this->executeResources->registerUrl($url);
		ob_start();
		try{
			$dispatcher=$this->router->createHttpTestDispatcher($this->executeResources->getEnviorment(),$this->executeResources->getGlobalConfig(),$this->executeResources->getUrl());
			$dispatcher->execute($this->executeResources,$this->eventManager);

		}
		catch(\Exception $e){
			$this->errorHandler->exception($e);
		}
		$content=ob_get_clean();
		ob_flush();

		$this->executeResources->getResponse()->setContent($content);
		return $this->executeResources->getResponse();

	}

	private function registerEvents(ExecuteResources $executeResources){
		foreach($executeResources->getGlobalConfig()->getEvents() as $bind=>$configs){
			foreach($configs as $config){
				$this->eventManager->register($bind,$config);
			}
		}

	}

	private function registerServices(ExecuteResources $executeResources){
		foreach($executeResources->getGlobalConfig()->getServices() as $service){
			$executeResources->registerService($service['name'] , new $service['class'](new ServiceConfig($service['config']),$this->eventManager));
		}
	}

	private function registerSnippets(ExecuteResources $executeResources){
		foreach($executeResources->getGlobalConfig()->getSnippets() as $snippet=>$class){
			$executeResources->registerSnippet($snippet,new $class());
		}
	}

}