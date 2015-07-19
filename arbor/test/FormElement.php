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

namespace Arbor\Test;

/**
 * Html Element for BrowserEmulator
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
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