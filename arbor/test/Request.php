<?php

namespace Arbor\Test;

use Arbor\Provider\Session;
use Arbor\Core\RequestProvider;
use Arbor\Exception\HeaderNotFoundException;
use Arbor\Core\Enviorment;
use Arbor\Root;
class Request implements RequestProvider{

	private $url;
	private $root;
	private $data=array();
	private $query=array();
	private $session;
	private $type='GET';
	private $headers=array();
	private $body;
	private $arguments=array();
	private $route;
	private $controller;
	private $class;
	private $method;
	private $presenter;
	private $extra=array();
	private $host;
	private $protocol;
	private $ssl=false;
	private $config;
	private $enviorment;
	public function __construct($url,$root,Enviorment $enviorment){
		$this->url=$url;
		$this->root=$root;
		$this->session=new Session($enviorment);
		$this->enviorment=$enviorment;
	}

	public function setConfig($config){
		$this->config=$config;
		$this->controller=$this->config->getController();
		$this->method=$this->config->getMethod();
		$this->class=$this->config->getClass();
		$this->extra=$this->config->getExtra();
		$this->presenter=$this->config->getPresenter();

	}

	public function execute(){
		$root=new Root($this->enviorment->isDebug(),$this->enviorment->isSilent(),$this->enviorment->getName()); //FIXME wygląda że trzeba będzie tak wywoływać testy funkcjonalne
		return $root->executeRequestTest($this);
	}

	public function getUrl(){
		return $this->url;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type=$type;
	}

	public function setData($data){
		$this->data=$data;
	}

	public function getHeader($name){
		if(!isset($this->headers[strtolower($name)]))
			throw new HeaderNotFoundException($name);
			
		return $this->headers[strtolower($name)]; 
	}

	public function setHeader($name,$value){
		$this->headers[strtolower($name)]=$value;
	}

	public function getBody(){
		return $this->body;
	}

	public function addArgument($value){
		$this->arguments[]=$value;
	}

	public function getArguments(){
		return $this->arguments;
	}

	public function removeArgument($index){
		$arguments=$this->arguments;
		$this->arguments=array();
		for($i=0; $i < count($arguments); $i++){
			if($i!==$index)
				$this->addArgument($arguments[$i]);
		}
	}

	public function getRoute(){
		return $this->route;
	}

	public function getSession(){
		return $this->session;
	}

	public function getClass(){
		return $this->class;
	}

	public function getController(){
		return $this->controller;
	}

	public function setController($controller){
		$this->controller=$controller;
	}

	public function getMethod(){
		return $this->method;
	}

	public function setMethod($method){
		$this->method=$method;
	}

	public function getPresenter(){
		return $this->presenter;
	}

	public function getExtra(){
		return $this->extra;
	}

	public function getData(){
		return $this->data;
	}

	public function getQuery(){
		return $this->query;
	}

	public function getHost(){
		return $this->host;
	}

	public function getProtocol(){
		return $this->protocol;
	}

	public function isSSL(){
		return $this->ssl;
	}
}