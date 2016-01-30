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
use Arbor\Exception\InvalidFieldNameException;

/**
 * Html Element for BrowserEmulator
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class FormElement extends HTMLElement{
	

	public function getFields($assoc=false){
		$fields=array();

		$fields=array_merge($fields,$this->findElements('input'));
		$fields=array_merge($fields,$this->findElements('textarea'));
		$fields=array_merge($fields,$this->findElements('select'));

		if(!$assoc){
			return $fields;
		}

		$assoc=array();
		foreach($fields as $field){
			$assoc[$field->getName()]=$field;
		}
		return $assoc;
	}

	public function getAction(){
		return $this->getAttribute('action');
	}

	public function submit(){
		$fields=$this->getFields();
		$data=array();
		foreach($fields as $field){
			$this->fillData($data,$field);
		}
		$url=($this->getAction()?$this->getAction():$this->browser->getUrl());
		$this->browser->requestPost($url,$data);
	}

	public function fillData(&$data,$field){
		$name=$field->getName();
		$value=$field->getData();
		if(preg_match('/^(.*?)(\[.*?\])+$/',$name,$matched)){ //array field
			if(!isset($data[$matched[1]])){
				$data[$matched[1]]=array();
			}

			$lastNode=&$data[$matched[1]];
			if(preg_match_all('/\[(.*?)\]/',$name,$matched)){
				$fieldParts=$matched[1];
				for($i=0; $i<count($fieldParts); $i++){
					$sectionName=$fieldParts[$i];

					if($sectionName==''){
						if($i==count($fieldParts)-1){
							if($value!=null){
								if(!is_array($value)){
									$value=array($value);
								}
								$lastNode=array_merge($lastNode,$value);
							}
						}
						else{
							$lastNode[]=array();
							$lastNode=&$lastNode[count($lastNode)-1];
						}

					}
					else{
						if($i==count($fieldParts)-1) {
//							if($value!=null) {
								$lastNode[$sectionName] = $value;
//							}
						}
						else if(!isset($lastNode[$sectionName])){
							$lastNode[$sectionName]=array();
							$lastNode=&$lastNode[$sectionName];
						}

					}

				}

			}
			else
				throw new InvalidFieldNameException();
		}
		else{
			$data[$name]=$value;
		}
	}
}