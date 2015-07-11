<?php

namespace Exception;

use Arbor\Core\Exception;

class DeviceTypeNotFoundException extends Exception{
	
	public function __construct(){
		parent::__construct(1,'Device type not found.');
	}

}