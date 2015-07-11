<?php

namespace Arbor\Parser;

class XMLParser implements ParserInterface{

	private $data;
	private $keys=array();
    private $position=0;

    public function __construct($xml=null){
    	if($xml){
			$this->parser($xml);
    	}
    }

	public function loadFromString($data){
		$xml=new \SimpleXMLElement($data);
		$this->parser($xml);
	}

	public function loadFromFile($data){
		$this->loadFromString(file_get_contents($data));

	}

	public function getValue($key){
		$values=$this->data->attributes();
		return $values[$key];
	}

    public function rewind() {
		$this->position = 0;
    }

    public function current() {
        return $this->data[$this->position];
    }

    public function key() {
        return $this->keys[$this->position];
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->data[$this->position]);
    }

    private function parser($xml){
		$this->data=array();
		foreach($xml->children() as $kChild=>$child){
			$elements=array();
			foreach($child as $element){
				$elements[]=new XMLParser($element);
			}

			$this->data[]=$elements;
			$this->keys[]=$kChild;

		}

    }
}