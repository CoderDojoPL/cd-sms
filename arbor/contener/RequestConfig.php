<?php

namespace Arbor\Contener;
use Arbor\Contener\GlobalConfig;

class RequestConfig{
	
	private $route;
	private $presenter;
	private $controller;
	private $method;
	private $extra;
	private $class;
	private $enviorment;

	public function __construct($controller , $method ,$enviorment , $actionConfig){
		$this->enviorment=$enviorment;
		$this->route=$actionConfig['route'];
		$this->presenter=$actionConfig['presenter'];
		$this->extra=(isset($actionConfig['extra'])?$actionConfig['extra']:array());
		$this->class=$actionConfig['class'];
		$this->controller=$controller;
		$this->method=$method;

	}

	public function getRoute(){
		return $this->route;
	}

	public function getClass(){
		return $this->class;
	}

	public function getPresenter(){
		return $this->presenter;
	}

	public function getController(){
		return $this->controller;
	}

	public function getMethod(){
		return $this->method;
	}

	public function getExtra(){
		return $this->extra;
	}

	public function isDebug(){
		return $this->enviorment->isDebug();
	}

	public function isSilent(){
		return $this->enviorment->isSilent();
	}

}