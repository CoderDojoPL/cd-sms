<?php

namespace Arbor\Exception;

use Arbor\Core\Exception;

class InvalidArgumentException extends Exception{
	
	public function __construct($position,$name,$message){
		parent::__construct(200+$position,'Niepoprawny argument "'.$name.'": '.$message);
	}
}
