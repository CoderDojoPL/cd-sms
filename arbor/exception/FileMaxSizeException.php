<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

/**
 * @since 0.18.0
 */
class FileMaxSizeException extends Exception{
	
	public function __construct($maxSize){
		parent::__construct(17,'Max size for file is too large. Server limit: '.$maxSize.'.');
	}
}