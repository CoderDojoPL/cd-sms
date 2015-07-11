<?php

namespace Arbor\Core;

use Arbor\Exception\ServiceNotFoundException;

abstract class Mapper{

	private $container;
	
	final public function __construct(Container $container){
		$this->container=$container;
	}

	final public function getService($name){
		return $this->container->getService($name);
	}

	abstract public function cast($value);
}