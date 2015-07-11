<?php

namespace Arbor\Validator;
use Arbor\Validator\Email;

/**
 * @since 0.17.0
 */
class EmailOrEmpty extends Email{

	public function validate($value){
		if($value)
			return parent::validate($value);
	}
	
}