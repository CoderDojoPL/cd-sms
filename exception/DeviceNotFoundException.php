<?php

namespace Exception;

use Arbor\Core\Exception;

class DeviceNotFoundException extends Exception{
	
	public function __construct(){
		parent::__construct(1,'Device not found.');
	}

}