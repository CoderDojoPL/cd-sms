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

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;

/**
 * FormBuilder field
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.15.0
 */
abstract class FormField{
	private $tags;
	private $validator;
	private $isValid=true;
	private $error;

	/**
	 * @param array $options - array with configura data. All field is optional eg:
	 * array(
	 * 	'name'=>'{text}' //tag name
	 * 	,'validator'=>'{text}' //validator class name
	 * 	,'id'=>'{text}' //tag id
	 * 	,'value'=>'{text}' //tag value
	 * 	,'required'=>{boolean} //tag required
	 * 	,...
	 * )
	 * @since 0.15.0
	 */
	public function __construct($options){

		if(isset($options['validator'])){
			$this->validator=$options['validator'];
			unset($options['validator']);
		}

		if(isset($options['label'])){
			$this->label=$options['label'];
			unset($options['label']);
		}

		$options+=array(
			'value'=>''
			,'name'=>null
			,'id'=>null
			,'class'=>''
			,'required'=>false
			);

		$this->tags=$options;

		$this->setRequired($this->isRequired());//invoke configure validator by execute seters

	}

	/**
	 * Set html tag name
	 *
	 * @param string $name - value of tag name:
	 * @since 0.15.0
	 */
	public function setName($name){
		$this->tags['name']=$name;
	}


	/**
	 * Get value of html tag name
	 *
	 * @return string
	 * @since 0.15.0
	 */
	public function getName(){
		return $this->tags['name'];
	}

	/**
	 * Set html tag id
	 *
	 * @param string $id - value of tag id:
	 * @since 0.15.0
	 */
	public function setId($id){
		$this->tags['id']=$id;
	}

	/**
	 * Get value of html tag id
	 *
	 * @return string
	 * @since 0.15.0
	 */
	public function getId(){
		return $this->tags['id'];
	}

	/**
	 * Set validator class rule
	 *
	 * @param \Arbor\Core\Validator $validator - validator class
	 * @since 0.15.0
	 */
	public function setValidator($validator=null){
		$this->validator=$validator;
	}


	/**
	 * Get validator class
	 *
	 * @return \Arbor\Core\Validator 
	 * @since 0.15.0
	 */
	public function getValidator(){
		return $this->validator;
	}

	/**
	 * Add part html tag class
	 *
	 * @param string $name - class name:
	 * @since 0.15.0
	 */
	public function addClass($name){
		$classParts=explode(' ',$this->tags['class']);
		foreach($classParts as $part){
			if($name==$part)
				return;
		}

		$this->tags['class'].=' '.$name;
		$this->tags['class']=trim($this->tags['class']);
	}

	/**
	 * Remove part html tag class
	 *
	 * @param string $name - class name:
	 * @since 0.15.0
	 */
	public function removeClass($name){
		$classParts=explode(' ',$this->tags['class']);
		$className='';
		foreach($classParts as $part){
			if($name!=$part){
				$className.=' '.$part;
			}
		}

		$this->tags['class']=trim($className);

	}

	/**
	 * Get value of html tag class
	 *
	 * @return string
	 * @since 0.15.0
	 */
	public function getClass(){
		return  $this->tags['class'];
	}

	/**
	 * Set label name for field
	 *
	 * @param string $label
	 * @since 0.15.0
	 */
	public function setLabel($label){
		$this->label=$label;
	}

	/**
	 * Get value of label field
	 *
	 * @return string
	 * @since 0.15.0
	 */
	public function getLabel(){
		return $this->label;
	}

	/**
	 * Set html tag required
	 *
	 * @param boolean $flag - if true then required else optional
	 * @since 0.15.0
	 */
	public function setRequired($flag){
		$this->tags['required']=$flag;
		if($this->validator){
			$this->validator->setOption('empty',!$flag);
		}
	}

	/**
	 * Get value of html tag required
	 *
	 * @return boolean
	 * @since 0.15.0
	 */
	public function isRequired(){
		return $this->tags['required'];		
	}

	/**
	 * Set html tag
	 *
	 * @param string $name - tag name
	 * @param mixed $value - value of tag
	 * @since 0.15.0
	 */
	public function setTag($name,$value){
		$this->tags[$name]=$value;
	}

	/**
	 * Get html tag
	 *
	 * @param string $name - tag name
	 * @return string|array
	 * @since 0.15.0
	 */
	public function getTag($name){
		return $this->tags[$name];
	}

	/**
	 * Get all html tags
	 *
	 * @return array
	 * @since 0.15.0
	 */
	public function getTags(){
		return $this->tags;
	}

	/**
	 * Check valid field
	 *
	 * @return boolean
	 * @since 0.17.0
	 */
	public function isValid(){
		return $this->isValid;
	}

	/**
	 * Get error message
	 *
	 * @return string
	 * @since 0.17.0
	 */
	public function getError(){
		return $this->error;
	}

	/**
	 * Set error message
	 *
	 * @param string $error - message
	 * @return string
	 * @since 0.17.0
	 */
	public function setError($error){
		$this->error=$error;
		$this->isValid=false;
	}

	/**
	 * Implement render html label and field
	 *
	 * @return string
	 * @since 0.15.0
	 */
	abstract public function render();

	/**
	 * Set confirmed data 
	 *
	 * @param mixed $data - confirmed data
	 * @since 0.16.0
	 */
	abstract public function setData($data);

	/**
	 * Get value field
	 *
	 * @return mixed
	 * @since 0.16.0
	 */
	abstract public function getData();

	/**
	 * Remove field data
	 *
	 * @param mixed $data - confirmed data
	 * @since 0.17.0
	 */
	abstract public function clearData();

	/**
	 * Implement render html field
	 *
	 * @return string
	 * @since 0.15.0
	 */
	abstract public function componentRender();

	/**
	 * implement render html label
	 *
	 * @return string
	 * @since 0.15.0
	 */
	abstract public function labelRender();

}