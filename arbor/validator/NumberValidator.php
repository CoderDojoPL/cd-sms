<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;
use Arbor\Exception\ValueNotFoundException;

/**
 * Validator for number
 * @since 0.18.0
 */
class NumberValidator extends Validator{
	
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

		if(!is_numeric($value))
			return 'Value is not number.';

		try{
			$min=$this->getOption('min');
			if($value<$min){
				return 'Value is too small.';
			}
		}
		catch(ValueNotFoundException $e){
			//ignore
		}

		try{
			$max=$this->getOption('max');
			if($value>$max){
				return 'Value is too height.';
			}
		}
		catch(ValueNotFoundException $e){
			//ignore
		}

	}
}
