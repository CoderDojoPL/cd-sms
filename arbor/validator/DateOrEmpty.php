<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;
use Arbor\Validator\Date;
	
class DateOrEmpty extends Date{
	
	public function validate($date){
		if($date==null || $date=='');
		else{
			return parent::validate($date);
		}
	}

}
