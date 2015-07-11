<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class SyntaxException extends Exception{
	
	public function __construct($errfile,$errline,$errstr){
		parent::__construct(12,$errstr,"Internal server error.",$errfile,$errline);
	}
}