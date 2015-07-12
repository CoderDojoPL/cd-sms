<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;
use Arbor\Validator\Date;

/**
 * @deprecated 0.18.0
 */		
class DateOrEmpty extends Date{
	
	public function validate($date){
		if($date==null || $date=='');
		else{
			return parent::validate($date);
		}
	}

}
