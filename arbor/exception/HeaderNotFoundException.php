<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class HeaderNotFoundException extends Exception{
	
	public function __construct($headerName){
		parent::__construct(11,"Header '".$headerName."' not found.","Internal server error.");
	}
}