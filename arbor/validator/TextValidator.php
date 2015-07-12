<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;
use Arbor\Exception\ValueNotFoundException;

/**
 * Validator for text
 * @since 0.18.0
 */
class TextValidator extends Validator{
	
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

		if(!$value && !$empty){
			return 'Value can not empty';
		}

		try{
			$pattern=$this->getOption('pattern');
			if(!preg_match('/'.$pattern.'/',$value)){
				return 'Invalid pattern format.';
			}

		}
		catch(ValueNotFoundException $e){
			//ignore
		}

	}
}
