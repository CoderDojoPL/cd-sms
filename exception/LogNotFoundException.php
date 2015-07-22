<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exception;

use Arbor\Core\Exception;

/**
 * Critical error. Modified entites but not created log record.
 * @package Exception
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class LogNotFoundException extends Exception{
	
	/**
	 * Construct
	 */
	public function __construct(){
		parent::__construct(1,'Log not found.','Internal server error.');
	}

}