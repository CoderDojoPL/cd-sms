<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class FileNotUploadedException extends Exception{
	
	public function __construct($name){
		parent::__construct(17,'File '.$name.' not uploaded.');
	}
}