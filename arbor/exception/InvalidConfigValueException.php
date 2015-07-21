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
 * Throw when Global config can not parse node value.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class InvalidConfigValueException extends Exception{
	
	/**
	 * Constructor.
	 *
	 * @param string $key
	 * @param string $value
	 * @since 0.1.0
	 */
	public function __construct($key,$value){
		parent::__construct(8,'Invalid config value \''.$value.'\' for key \''.$key.'\'.','Invalid configuration.');
	}
}