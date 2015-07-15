<?php

namespace Arbor\Test;
use Arbor\Test\FormFieldElement;

class InputElement extends FormFieldElement{
	
	public function getData(){
		return $this->getAttribute('value');
	}

}