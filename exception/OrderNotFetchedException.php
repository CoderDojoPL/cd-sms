<?php

namespace Exception;

use Arbor\Core\Exception;

class OrderNotFetchedException extends Exception{
	
	public function __construct(){
		parent::__construct(1,'Order not fetched.');
	}

}