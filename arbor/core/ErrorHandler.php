<?php

namespace Arbor\Core;
use Arbor\Root;
use Arbor\Provider\Response;
use Arbor\Presenter\HTML as HTMLPresenter;
use Arbor\Exception\SyntaxException;
use Arbor\Exception\RuntimeException;
use Arbor\Core\ExecuteResources;
use Arbor\Exception\ResourcesNotRegisteredException;
use Arbor\Contener\RequestConfig;
use Arbor\Presenter\HTML;
use Arbor\Event\ExecutePresenterEvent;
use Arbor\Provider\Request;
use Arbor\Provider\Session;

class ErrorHandler{
	
	private $root;
	private $isStopPropagation;
	private $runtimePath;
	private $eventManager;
	public function __construct(ExecuteResources $root,$eventManager){
		$this->root=$root;
		$this->eventManager=$eventManager;
		$this->isStopPropagation=false;
		$this->runtimePath=getcwd();

		ini_set('display_errors', 'Off');
		register_shutdown_function(array($this, 'shutdown'));
		set_error_handler(array($this, 'error'));
		set_exception_handler(array($this,'exception'));
	}

	public function shutdown(){
		if($this->isStopPropagation)
			return;
		$error = error_get_last();
		if( $error !== NULL) {
			chdir($this->runtimePath);
			$errno   = $error["type"];
			$errfile = $error["file"];
			$errline = $error["line"];
			$errstr  = $error["message"];


			$this->parseView(new SyntaxException($errfile,$errline,$errstr));
		}
	}

	public function exception($exception){
		if($this->isStopPropagation)
			return;
		$this->parseView($exception);

		$this->isStopPropagation=true;
		return false;
	}
		
	public function error($errno, $errstr, $errfile, $errline){
		throw new RuntimeException($errfile,$errline,$errstr);
	}

	private function parseView($exception){
		error_log($exception->getMessage()." ".$exception->getFile()."(".$exception->getLine().")");
		$response=new Response();
		$response->setStatusCode(500);
		$response->setContent($exception);
		$this->root->registerResponse($response);
		$presenter=null;

		try{
			$presenter=$this->root->getPresenter();
		}
		catch(ResourcesNotRegisteredException $e){
			$presenter=$this->findPresenter();
		}

		try{
			$request=$this->root->getRequest();
		}
		catch(ResourcesNotRegisteredException $e){
			$requestConfig=new RequestConfig('','',$this->root->getEnviorment(),
					array(
						'route'=>''
						,'presenter'=>array('class' =>'')
						,'class'=>''
						));

			$session=new Session($this->root->getEnviorment());
			$request=new Request($requestConfig,$this->root->getUrl(),$session);
			$this->root->registerRequest($request);
		}

		if($presenter){
			$event=new ExecutePresenterEvent($this->root->getRequest(),$response);
			$this->eventManager->fire('executePresenter',$event);
			$presenter->render($request->getConfig() , $response);
		}

	}

	private function findPresenter(){
		$url=$this->root->getUrl();
		if($this->root->getGlobalConfig()){
			foreach($this->root->getGlobalConfig()->getErrors() as $pattern=>$presenterName){
				if(preg_match('/^'.$pattern.'$/',$url)){
					return new $presenterName();
				}
			}

		}

		return new HTML();

	}

}