<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;

/**
 * @since 0.15.0
 */
abstract class InputField extends FormField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		parent::__construct($options);
	}

	/**
	 * set html tag pattern
	 * @param string pattern - value of tag pattern (regular expression)
	 * @since 0.16.0
	 */
	public function setPattern($pattern){
		$this->setTag('pattern',$pattern);
	}

	/**
	 * get value of html tag pattern
	 * @return string
	 * @since 0.16.0
	 */
	public function getPattern(){
		return $this->getTag('pattern');		
	}

	/**
	 * set value field
	 * @param mixed $value - value field
	 * @since 0.16.0
	 */
	public function setValue($value){
		$this->setTag('value',$value);
	}

	/**
	 * get value field
	 * @return string|array
	 * @since 0.16.0
	 */
	public function getValue(){
		return $this->getTag('value');		
	}

    /**
     * {@inheritdoc}
     */
	public function render(){
		$template=$this->labelRender();
		$template.=$this->componentRender();
		return $template;
	}

    /**
     * {@inheritdoc}
     */
	public function labelRender(){
		return '<label for="'.$this->getId().'">'.$this->getLabel().'</label>';
	}

    /**
     * {@inheritdoc}
     */
	public function componentRender(){
		$template='<input ';
		foreach($this->getTags() as $kTag=>$tag){
			if($tag!='')
				$template.=$kTag.'="'.$tag.'" ';
		}
		$template.=' />';
		return $template;

	}
	
    /**
     * {@inheritdoc}
     */
	public function setData($value){
		$this->setValue($value);
	}

    /**
     * {@inheritdoc}
     */
	public function getData(){
		return $this->getValue();
	}

    /**
     * {@inheritdoc}
     */
	public function clearData(){
		return $this->setValue(null);
	}


}