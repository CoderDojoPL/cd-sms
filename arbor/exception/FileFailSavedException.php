<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class FileFailSavedException extends Exception{
	
	public function __construct($reason){
		parent::__construct(10,"File fail saved. Reason: ".$reason,"Internal server error.");
	}
}