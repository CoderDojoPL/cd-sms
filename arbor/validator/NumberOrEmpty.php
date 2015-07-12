<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;
use Arbor\Validator\Number;
	
/**
 * @deprecated 0.18.0
 */
class NumberOrEmpty extends Number{
	
	public function validate($date){
		if($date==null || $date=='');
		else{
			return parent::validate($date);
		}
	}

}
