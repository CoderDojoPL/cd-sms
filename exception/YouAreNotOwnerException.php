<?php

namespace Exception;

use Arbor\Core\Exception;

class YouAreNotOwnerException extends Exception{
	
	public function __construct(){
		parent::__construct(1,'You are not owner.');
	}

}