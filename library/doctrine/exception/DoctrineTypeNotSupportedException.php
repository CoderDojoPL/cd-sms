<?php

namespace Library\Doctrine\Exception;

class DoctrineTypeNotSupportedException extends \Exception{
	
	public function __construct($type){
		parent::__construct('Doctrine type '.$type.' not supported.');
	}
}