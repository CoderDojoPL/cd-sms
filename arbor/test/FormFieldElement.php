<?php

namespace Arbor\Test;

abstract class FormFieldElement extends HTMLElement{
	
	public function getName(){
		return $this->getAttribute('name');
	}

	abstract public function getData();

}