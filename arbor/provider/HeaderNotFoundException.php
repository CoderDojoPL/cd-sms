<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class HeaderNotFoundException extends Exception{
	
	public function __construct($name){
		parent::__construct(8,'Header "'.$name.'" not found.','Internal server error.');
	}
}