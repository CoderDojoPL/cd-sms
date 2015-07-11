<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class InvalidStatusCodeException extends Exception{
	
	public function __construct($statusCode){
		parent::__construct(7,'Invalid status code "'.$statusCode.'".','Internal server error.');
	}
}