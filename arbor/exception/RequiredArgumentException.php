<?php

namespace Arbor\Exception;

use Arbor\Core\Exception;

class RequiredArgumentException extends Exception{
	
	public function __construct($position,$name){
		parent::__construct(100+$position,'Wymagany argument "'.$name.'".');
	}
}
