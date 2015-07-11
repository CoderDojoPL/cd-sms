<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class ResourcesNotRegisteredException extends Exception{
	
	public function __construct(){
		parent::__construct(12,"Resources not registered.","Internal server error.");
	}
}