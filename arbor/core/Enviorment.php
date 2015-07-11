<?php

namespace Arbor\Core;

class Enviorment{

	private $debug;
	private $silent;
	private $name;

	public function __construct($debug,$silent,$name){
		$this->debug=$debug;
		$this->silent=$silent;
		$this->name=$name;
	}

	public function isDebug(){
		return $this->debug;
	}	

	public function isSilent(){
		return $this->silent;
	}

	public function getName(){
		return $this->name;
	}
}