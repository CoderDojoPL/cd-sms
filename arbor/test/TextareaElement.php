<?php

namespace Arbor\Test;
use Arbor\Test\FormFieldElement;

class TextareaElement extends FormFieldElement{
	private $data;

	public function getData(){
		if($this->data!==null){
			return $this->data;
		}

		return $this->getHtml();
	}
	

	public function setData($data){
		$this->data=$data;
		return $this;
	}

}