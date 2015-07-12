<?php

namespace Exception;

use Arbor\Core\Exception;

class DeviceStateNotFoundException extends Exception{
	
	public function __construct(){
		parent::__construct(1,'Device state not found.');
	}

}