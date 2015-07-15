<?php

namespace Arbor\Test;
use Arbor\Test\FormFieldElement;

class SelectElement extends FormFieldElement{
	private $data;
	
	public function getData(){
		if($this->data!==null){
			return $this->data;
		}

		foreach($this->findElements('option') as $option){
			if($option->getAttribute('selected')!=''){
				return $option->getAttribute('value');
			}
		}

		return null;
	}

	public function setData($data){
		$this->data=$data;
		return $this;
	}

}