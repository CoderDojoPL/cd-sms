<?php

namespace Arbor\Core;

use Arbor\Exception\ValueNotFoundException;
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