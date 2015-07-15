<?php

namespace Arbor\Test;
use Arbor\Test\FormFieldElement;

class TextareaElement extends FormFieldElement{
	
	public function getData(){
		return $this->getHtml();
	}

}