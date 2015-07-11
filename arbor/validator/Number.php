<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;

class Number implements Validator{
	
	public function validate($value){
		if($value==null || !is_numeric($value))
			return 'Value can not number.';
	}
}
