<?php

namespace Arbor\Test;
use Arbor\Test\FormFieldElement;

class SelectElement extends FormFieldElement{
	
	public function getData(){
		return $this->getAttribute('value');
	}

}