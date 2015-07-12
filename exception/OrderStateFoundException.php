<?php

namespace Exception;

use Arbor\Core\Exception;

class OrderStateFoundException extends Exception{
	
	public function __construct(){
		parent::__construct(1,'Order state not found.');
	}

}