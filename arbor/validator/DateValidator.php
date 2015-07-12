<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;
use Arbor\Exception\ValueNotFoundException;

/**
 * Validator for date
 * @since 0.18.0
 */
class DateValidator extends Validator{
	
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

		$d = \DateTime::createFromFormat('Y-m-d', $value);
	    if($d && $d->format('Y-m-d') == $value);
	    else
			return 'Invalid date format.';
	}

}
