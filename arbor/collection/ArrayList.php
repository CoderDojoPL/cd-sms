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

namespace Arbor\Collection;

use \ArrayAccess;
use \Iterator;
use Arbor\Exception\ValueNotFound;
/**
 * Wrapper of array
 *
 * @package Arbor\Collection
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class ArrayList implements ArrayAccess,Iterator{

	/**
	 * Array with records.
	 *
	 * @var array $data
	 */
	private $data=array();

	/**
	 * Constructor.
	 *
	 * @param array $arrays
	 * @since 0.1.0
	 */
	public function __construct($arrays=array()){
		foreach($arrays as $key=>$array){
			$value=$array;
			if(gettype($value)=='array'){

				if($this->isAssoc($array)){
					$value=new Map($value);
				}
				else{
					$value=new ArrayList($value);
				}
			}

			$this->data[$key]=$value;
		}
	}

	/**
	 * Get element.
	 *
	 * @param string $key
	 * @return mixed
	 * @throws \Arbor\Exception\ValueNotFound
	 * @since 0.1.0
	 */
	public function get($key){
		if(!isset($this->data[$key])){
			throw new ValueNotFound($key);
		}

		return $this->data[$key];
	}

	/**
	 * Check exist index.
	 *
	 * @param int $offset
	 * @return boolean
	 * @since 0.1.0
	 */
	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}

	/**
	 * Remove index.
	 *
	 * @param int $offset
	 * @since 0.1.0
	 */
	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}

	/**
	 * Get index data.
	 *
	 * @param int $offset
	 * @return mixed|null
	 * @since 0.1.0
	 */
	public function offsetGet($offset) {
		return isset($this->data[$offset]) ? $this->data[$offset] : null;
	}

	/**
	 * Set index data.
	 *
	 * @param int $offset
	 * @param mixed $value
	 * @since 0.1.0
	 */
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->data[] = $value;
		} else {
			$this->data[$offset] = $value;
		}
	}    

	/**
	 * Reset index.
	 *
	 * @since 0.1.0
	 */
	public function rewind(){
		reset($this->data);
	}

	/**
	 * Get current index data.
	 *
	 * @return mixed
	 * @since 0.1.0
	 */
	public function current(){
		return current($this->data);
	}

	/**
	 * Get current index.
	 *
	 * @return int
	 * @since 0.1.0
	 */
	public function key(){
		return key($this->data);
	}

	/**
	 * Shift index and get next value.
	 *
	 * @return mixed
	 * @since 0.1.0
	 */
	public function next(){
		return next($this->data);
	}

	/**
	 * Get index data.
	 *
	 * @return boolean
	 * @since 0.1.0
	 */
	public function valid(){
		$key = key($this->data);
		$var = ($key !== NULL && $key !== FALSE);
		return $var;
	}

	/**
	 * Generate json string.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function __toString(){
		return json_encode($this->data);
	}

	/**
	 * Check array type.
	 *
	 * @param array $arr
	 * @return boolean
	 * @since 0.1.0
	 */
	private function isAssoc($arr){
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}