<?php

namespace Arbor\Core;
use Arbor\Core\ExecuteResources;

class EventManager{
	
	private $resources;
	private $events=array();
	private $cacheClasses=array();

	public function __construct(ExecuteResources $resources){
		$this->resources=$resources;
	}

	public function register($event,$config){
		$this->events+=array($event=>array());
		$this->events[$event][]=$config;
	}

	public function fire($event,$infoClass=null){
		if(isset($this->events[$event])){
			foreach($this->events[$event] as $bind=>$config){
				$className=$config['class'];
				if(!isset($this->cacheClasses[$className]))
					$this->cacheClasses[$className]=new $className($this->resources);
				call_user_func_array(array($this->cacheClasses[$className], $config['method']), array($infoClass,$config['config']));
			}
		}
	}

}