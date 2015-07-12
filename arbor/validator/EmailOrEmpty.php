<?php

namespace Arbor\Validator;
use Arbor\Validator\Email;

/**
 * @deprecated 0.18.0
 * @since 0.17.0
 */
class EmailOrEmpty extends Email{

	public function validate($value){
		if($value)
			return parent::validate($value);
	}
	
}