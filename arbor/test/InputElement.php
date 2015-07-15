<?php

namespace Arbor\Test;
use Arbor\Test\FormFieldElement;

class InputElement extends FormFieldElement{
	private $data;

	public function getData(){
		if($this->data!==null){
			return $this->data;
		}

		return $this->getAttribute('value');
	}

	public function setData($data){
		$this->data=$data;
		return $this;
	}

}