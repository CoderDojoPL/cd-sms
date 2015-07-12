<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;

/**
 * @deprecated 0.18.0
 * @since 0.17.0
 */
class Email extends Validator{

	public function validate($value){
		if(!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+$/",$value))
			return "Niepoprawny format email.";
	}
	
}