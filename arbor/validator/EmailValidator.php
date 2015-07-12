<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;
use Arbor\Exception\ValueNotFoundException;

/**
 * Validator for email
 * @since 0.18.0
 */
class EmailValidator extends Validator{

	/**
	 * {@inheritdoc}
	 */
	public function validate($value){

		$empty=false;

		try{
			$empty=$this->getOption('empty');
		}
		catch(ValueNotFoundException $e){
			//ignore
		}

		if(!$value && $empty){
			return;
		}

		if(!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+$/",$value))
			return "Invalid email format.";
	}
	
}