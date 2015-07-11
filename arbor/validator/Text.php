<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;

class Text implements Validator{
	
	public function validate($value){
		if($value==null || $value=='')
			return 'Value can not empty.';
	}
}
