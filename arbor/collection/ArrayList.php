<?php

namespace Arbor\Collection;
use \ArrayAccess;
use \Iterator;

class ArrayList implements ArrayAccess,Iterator{
	private $data=array();

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

	public function get($key){
		if(!isset($this->data[$key])){
			throw new ValueNotFound($key);
		}

		return $this->data[$key];
	}

	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}
	public function offsetGet($offset) {
		return isset($this->data[$offset]) ? $this->data[$offset] : null;
	}

	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->data[] = $value;
		} else {
			$this->data[$offset] = $value;
		}
	}    

	public function rewind(){
		reset($this->data);
	}

	public function current(){
		return current($this->data);
	}

	public function key(){
		return key($this->data);
	}

	public function next(){
		return next($this->data);
	}

	public function valid(){
		$key = key($this->data);
		$var = ($key !== NULL && $key !== FALSE);
		return $var;
	}

	public function __toString(){
		return json_encode($this->data);
	}

	private function isAssoc($arr){
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}