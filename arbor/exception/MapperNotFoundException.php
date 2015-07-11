<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class MapperNotFoundException extends Exception{
	
	public function __construct($mapperName){
		parent::__construct(4,'Mapper "'.$mapperName.'" not found.','Internal server error.');
	}
}