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

namespace Arbor\Exception;

use Arbor\Core\Exception;

/**
 * Throw when BrowserEmulator has empty response.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class EmptyDataException extends Exception{
	
	/**
	 * Constructor
	 *
	 * @since 0.1.0
	 */	
	public function __construct(){
		parent::__construct(19,'Empty data.');
	}
}
