<?php

namespace Exception;

use Arbor\Core\Exception;

class OrderAllreadyFetchedException extends Exception{
	
	public function __construct(){
		parent::__construct(1,'Order allready fetched.');
	}

}