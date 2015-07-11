<?php

namespace Arbor\Exception;

use Arbor\Core\Exception;

class ConfigAttributeNotFoundException extends Exception{
	
	public function __construct($key){
		parent::__construct(1,'Config atribute \''.$key.'\' not found.','Invalid configuration.',"Internal server error.");
	}
}