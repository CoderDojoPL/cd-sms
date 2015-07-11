<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class RuntimeException extends Exception{
	
	public function __construct($errfile,$errline,$errstr){
		parent::__construct(13,$errstr,"Internal server error.",$errfile,$errline);
	}
}