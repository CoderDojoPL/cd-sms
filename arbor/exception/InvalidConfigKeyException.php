<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class InvalidConfigKeyException extends Exception{
	
	public function __construct($key){
		parent::__construct(2,'Invalid config key \''.$key.'\'.','Invalid configuration.',"Internal server error.");
	}
}