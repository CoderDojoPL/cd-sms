<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;

/**
 * @deprecated 0.18.0
 */
class Number extends Validator{
	
	public function validate($value){
		if($value==null || !is_numeric($value))
			return 'Value can not number.';
	}
}
