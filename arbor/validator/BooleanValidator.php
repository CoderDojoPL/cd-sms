<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;
use Arbor\Exception\ValueNotFoundException;

/**
 * Validator for number
 * @since 0.18.0
 */
class BooleanValidator extends Validator{
	
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

		if((!$value || $value=='false') && $empty){
			return;
		}

		if(!in_array($value, array('true','on',true))){
			return "Invalid value.";
		}
	}
}