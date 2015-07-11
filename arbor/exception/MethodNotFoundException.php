<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class MethodNotFoundException extends Exception{
	
	public function __construct($className,$methodName){
		parent::__construct(9,"Method '".$className."::".$methodName."' not found.","Internal server error.");
	}
}