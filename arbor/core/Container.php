<?php

namespace Arbor\Core;

use Arbor\Exception\ServiceNotFoundException;
use Arbor\Exception\MethodNotFoundException;
use Arbor\Core\ExecuteResources;

class Container{

	private $services=array();
	private $snippets=array();	

	public function __construct(ExecuteResources $executeResources){
		$this->executeResources=$executeResources;
	}

	public function getEnviorment(){
		return $this->executeResources->getEnviorment();
	}

	public function getService($name){
		$services=$this->executeResources->getServices();
		if(!isset($services[$name]))
			throw new ServiceNotFoundException($name);

		return $services[$name];
	}

	public function __call($method, $args){
		$snippets=$this->executeResources->getSnippets();
		if(isset($snippets[$method])){
	        return call_user_func_array(array($snippets[$method], $method),
            array_merge(array($this),$args)
			);

		}
		else{
			throw new MethodNotFoundException(get_class($this),$method);
		}

    }
}