<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class CommandNotFoundException extends Exception{
	
	public function __construct($commandName){
		parent::__construct(10,"Command '".$commandName."' not found.","Internal server error.");
	}
}