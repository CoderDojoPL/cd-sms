<?php

namespace Arbor\Core;
use Arbor\Contener\GlobalConfig;
use Arbor\Core\Presenter;
use Arbor\Contener\RequestConfig;
use Arbor\Provider\Response;
use Arbor\Provider\Request;
use Arbor\Exception\ResourcesNotRegisteredException;

class ExecuteResources{
	
	private $globalConfig;
	private $services=array();
	private $snippets=array();
	private $presenter;
	private $requestConfig;
	private $request;
	private $response;
	private $url;
	private $enviorment;

	public function registerGlobalConfig(GlobalConfig $config){
		$this->globalConfig=$config;
	}

	public function getGlobalConfig(){
		return $this->globalConfig;
	}

	public function registerService($name,$object){
		$this->services[$name]=$object;
	}

	public function registerSnippet($name,$object){
		$this->snippets[$name]=$object;
	}

	public function registerPresenter(Presenter $presenter){
		$this->presenter=$presenter;
	}

	public function getPresenter(){
		if($this->presenter)
			return $this->presenter;
		else
			throw new ResourcesNotRegisteredException();
	}

	public function registerEnviorment(Enviorment $enviorment){
		$this->enviorment=$enviorment;
	}

	public function getEnviorment(){
		return $this->enviorment;
	}

	public function registerRequest(Request $request){
		$this->request=$request;
	}

	public function getRequest(){
		if($this->request)
			return $this->request;
		else
			throw new ResourcesNotRegisteredException();
	}

	public function registerResponse(Response $response){
		$this->response=$response;
	}

	public function getResponse(){
		if($this->response)
			return $this->response;
		else
			throw new ResourcesNotRegisteredException();
	}

	public function getServices(){
		return $this->services;
	}

	public function getSnippets(){
		return $this->snippets;
	}

	public function registerUrl($url){
		$this->url=$url;
	}

	public function getUrl(){
		return $this->url;
	}

	public function setDebug($debug){
		$this->debug=$debug;
	}

	public function isDebug(){
		return $this->debug;
	}

	public function setSilent($silent){
		$this->silent=$silent;
	}

	public function isSilent(){
		return $this->silent;
	}

}