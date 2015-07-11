<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class InvalidConfigValueException extends Exception{
	
	public function __construct($key,$value){
		parent::__construct(8,'Invalid config value \''.$value.'\' for key \''.$key.'\'.','Invalid configuration.');
	}
}