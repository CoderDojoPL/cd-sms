<?php

namespace Arbor\Test;

class FormElement extends HTMLElement{
	

	public function getFields(){
		$fields=array();

		$fields=array_merge($fields,$this->findElements('input'));
		$fields=array_merge($fields,$this->findElements('textarea'));
		$fields=array_merge($fields,$this->findElements('select'));

		return $fields;
	}

	public function getAction(){
		return $this->getAttribute('action');
	}

	public function submit(){
		$fields=$this->getFields();
		$data=array();
		foreach($fields as $field){
			$data[$field->getName()]=$field->getData();
		}

		$url=($this->getAction()?$this->getAction():$this->browser->getUrl());
		$this->browser->requestPost($url,$data);
	}
}