<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;

/**
 * @deprecated 0.18.0
 */	
class Date extends Validator{
	
	public function validate($date){

		$d = \DateTime::createFromFormat('Y-m-d', $date);
	    if($d && $d->format('Y-m-d') == $date);
	    else
			return 'Invalid date format.';
	}

}
