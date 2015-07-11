<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class ValueNotFoundException extends Exception{
	
	public function __construct($name){
		parent::__construct(6,'Value "'.$name.'" not found.','Internal server error.');
	}
}