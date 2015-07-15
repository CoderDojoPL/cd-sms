<?php

namespace Arbor\Exception;

use Arbor\Core\Exception;

class EmptyDataException extends Exception{
	
	public function __construct(){
		parent::__construct(19,'Empty data.');
	}
}
