<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class ServiceNotFoundException extends Exception{
	
	public function __construct($service){
		parent::__construct(5,'Service "'.$service.'" not found.','Internal server error.');
	}
}