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

namespace Arbor\Parser;

/**
 * Xml file parser.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 * @deprecated 0.10.0
 */
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