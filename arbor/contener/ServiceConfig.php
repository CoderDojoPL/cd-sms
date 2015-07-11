<?php

namespace Arbor\Contener;

class ServiceConfig{
	
	private $config;

	public function __construct($config){
		$this->config=$config;
	}

	public function get($key){
		return $this->config[$key];
	}
}