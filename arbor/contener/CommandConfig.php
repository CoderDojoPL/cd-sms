<?php

namespace Arbor\Contener;
use Arbor\Contener\GlobalConfig;

class CommandConfig{
	
	private $command;
	private $method;
	private $class;

	public function __construct($command , $class,$method){

		$this->class=$class;
		$this->command=$command;
		$this->method=$method;

	}

	public function getCommand(){
		return $this->command;
	}

	public function getMethod(){
		return $this->method;
	}

	public function getClass(){
		return $this->class;
	}
}