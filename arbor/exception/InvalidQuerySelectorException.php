<?php

namespace Arbor\Exception;

use Arbor\Core\Exception;

class InvalidQuerySelectorException extends Exception{
	
	public function __construct($query){
		parent::__construct(20,'Invalid query selector: '.$query);
	}
}
