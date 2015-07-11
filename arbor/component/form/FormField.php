<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;

/**
 * @since 0.15.0
 */
abstract class FormField{
	private $tags;
	private $validator;
	private $isValid=true;
	private $error;

	/**
	 * @param options - array with configura data. All field is optional eg:
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
			,'pattern'=>null
			);

		$this->tags=$options;
	}

	/**
	 * set html tag name
	 * @param name - value of tag name:
	 * @since 0.15.0
	 */
	public function setName($name){
		$this->tags['name']=$name;
	}


	/**
	 * get value of html tag name
	 * @return string
	 * @since 0.15.0
	 */
	public function getName(){
		return $this->tags['name'];
	}

	/**
	 * set html tag id
	 * @param id - value of tag id:
	 * @since 0.15.0
	 */
	public function setId($id){
		$this->tags['id']=$id;
	}

	/**
	 * get value of html tag id
	 * @return string
	 * @since 0.15.0
	 */
	public function getId(){
		return $this->tags['id'];
	}

	/**
	 * set validator class rule
	 * @param string validator - validator class name
	 * @since 0.15.0
	 */
	public function setValidator($validator=null){
		$this->validator=$validator;
	}


	/**
	 * get validator class name
	 * @return string
	 * @since 0.15.0
	 */
	public function getValidator(){
		return $this->validator;
	}

	/**
	 * add part html tag class
	 * @param string name - class name:
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
	 * remove part html tag class
	 * @param string name - class name:
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
	 * get value of html tag class
	 * @return string
	 * @since 0.15.0
	 */
	public function getClass(){
		return  $this->tags['class'];
	}

	/**
	 * set label name for field
	 * @param string label - label name
	 * @since 0.15.0
	 */
	public function setLabel($label){
		$this->label=$label;
	}

	/**
	 * get value of label field
	 * @return string
	 * @since 0.15.0
	 */
	public function getLabel(){
		return $this->label;
	}

	/**
	 * set html tag required
	 * @param boolean flag - if true then required else optional
	 * @since 0.15.0
	 */
	public function setRequired($flag){
		$this->tags['required']=$flag;
	}

	/**
	 * get value of html tag required
	 * @return boolean
	 * @since 0.15.0
	 */
	public function isRequired(){
		return $this->tags['required'];		
	}

	/**
	 * set html tag
	 * @param string name - tag name
	 * @param mixed value - value of tag
	 * @since 0.15.0
	 */
	public function setTag($name,$value){
		$this->tags[$name]=$value;
	}

	/**
	 * get html tag
	 * @param string name - tag name
	 * @return string|array
	 * @since 0.15.0
	 */
	public function getTag($name){
		return $this->tags[$name];
	}

	/**
	 * get all html tags
	 * @return array
	 * @since 0.15.0
	 */
	public function getTags(){
		return $this->tags;
	}

	/**
	 * check valid field
	 * @return bool
	 * @since 0.17.0
	 */
	public function isValid(){
		return $this->isValid;
	}

	/**
	 * get error message
	 * @return string
	 * @since 0.17.0
	 */
	public function getError(){
		return $this->error;
	}

	/**
	 * set error message
	 * @param string $error - message
	 * @return string
	 * @since 0.17.0
	 */
	public function setError($error){
		$this->error=$error;
		$this->isValid=false;
	}

	/**
	 * implement render html label and field
	 * @return string
	 * @since 0.15.0
	 */
	abstract public function render();

	/**
	 * set confirmed data 
	 * @param mixed $data - confirmed data
	 * @since 0.16.0
	 */
	abstract public function setData($data);

	/**
	 * get value field
	 * @return mixed
	 * @since 0.16.0
	 */
	abstract public function getData();

	/**
	 * remove field data 
	 * @param mixed $data - confirmed data
	 * @since 0.17.0
	 */
	abstract public function clearData();

	/**
	 * implement render html field
	 * @return string
	 * @since 0.15.0
	 */
	abstract public function componentRender();

	/**
	 * implement render html label
	 * @return string
	 * @since 0.15.0
	 */
	abstract public function labelRender();

}