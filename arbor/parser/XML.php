<?php

namespace Arbor\Parser;

class XML{
	
	public function __construct($data,$type='FILE'){
		if($type=='FILE')
			$this->data=new \SimpleXMLElement(file_get_contents($data));
		else
			$this->data=new \SimpleXMLElement($data);
	}

	public function get(){
		return $this->data;
	}

	public function getChild($name){
		$data=$this->data->children();
		return $data[0];//TODO dorobić wykrywanie "obiekt nie istnieje"
	}
}