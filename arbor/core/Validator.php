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

namespace Arbor\Core;

use Arbor\Exception\ValueNotFoundException;

/**
 * Main class for validators. Check correct values.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
abstract class Validator{

	private $options=array();
	
	/**
	 * Implement method to validate value
	 * @param mixed $value - value to parse
	 * @return string|null - error message
	 */
	abstract public function validate($value);

	/**
	 * Set extra options
	 * @param mixed $value - value to parse
	 * @return string|null - error message
	 * @since 0.18.0
	 */
	final public function setOption($name,$value){
		$this->options[$name]=$value;
	}

	/**
	 * Get extra option
	 * @param string $name - name of option
	 * @return mixed - option
	 * @throws Arbor\Exception\ValueNotFoundException
	 * @since 0.18.0
	 */
	protected function getOption($name){
		if(!isset($this->options[$name])){
			throw new ValueNotFoundException($name);
		}

		return $this->options[$name];
	}
}