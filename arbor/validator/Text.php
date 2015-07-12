<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;

/**
 * @deprecated 0.18.0
 */
class Text extends Validator{
	
	public function validate($value){
		if($value==null || $value=='')
			return 'Value can not empty.';
	}
}
