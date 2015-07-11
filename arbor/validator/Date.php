<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;
	
class Date implements Validator{
	
	public function validate($date){

		$d = \DateTime::createFromFormat('Y-m-d', $date);
	    if($d && $d->format('Y-m-d') == $date);
	    else
			return 'Invalid date format.';
	}

}
