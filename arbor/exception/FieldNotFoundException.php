<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

/**
 * @since 0.16.0
 */
class FieldNotFoundException extends Exception{
	
	/**
	 * @param string name - field name
	 * @since 0.16.0
	 */
	public function __construct($name){
		parent::__construct(4,'Field "'.$name.'" not found.','Internal server error.');
	}
}