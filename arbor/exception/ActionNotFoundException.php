<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class ActionNotFoundException extends Exception{
	
	public function __construct($action , $action){
		parent::__construct(4,'Action "'.$action.'" for action "'.$action.'" not found.','Internal server error.');
	}
}