<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;
use Arbor\Validator\Number;
	
class NumberOrEmpty extends Number{
	
	public function validate($date){
		if($date==null || $date=='');
		else{
			return parent::validate($date);
		}
	}

}
