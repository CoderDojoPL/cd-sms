<?php

/*
 * This file is part of the ArborPHP.
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Library\Doctrine\Exception;

class DoctrineTypeNotSupportedException extends \Exception{
	
	public function __construct($type){
		parent::__construct('Doctrine type '.$type.' not supported.');
	}
}