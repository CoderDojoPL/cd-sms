<?php

namespace Arbor\Exception;

use Arbor\Core\Exception;

class PermissionDeniedException extends Exception{
	
	public function __construct(){
		parent::__construct(311,'Odmowa dostępu!');
	}
}
