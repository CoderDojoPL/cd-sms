<?php

namespace Arbor\Collection;
use \ArrayAccess;

class Map implements ArrayAccess{
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
			throw new ValueNotFoundException($key);
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

	private function isAssoc($arr){
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}