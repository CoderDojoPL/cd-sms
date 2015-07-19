<?php

/**
 * ArborPHP: Freamwork PHP (http://arborphp.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://arborphp.com ArborPHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

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
