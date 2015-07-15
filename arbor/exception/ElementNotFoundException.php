<?php

namespace Arbor\Exception;

use Arbor\Core\Exception;

class ElementNotFoundException extends Exception{
	
	public function __construct(){
		parent::__construct(20,'Element not found.');
	}
}
