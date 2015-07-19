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
abstract class InputField extends FormField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		parent::__construct($options);
	}

	/**
	 * Set html tag pattern
	 *
	 * @param string $pattern - value of tag pattern (regular expression)
	 * @since 0.16.0
	 */
	public function setPattern($pattern){
		$this->setTag('pattern',$pattern);
		if($this->getValidator()){
			$this->getValidator()->setOption('pattern',$pattern);
		}
	}

	/**
	 * Get value of html tag pattern
	 *
	 * @return string
	 * @since 0.16.0
	 */
	public function getPattern(){
		return $this->getTag('pattern');		
	}

	/**
	 * Set value field
	 *
	 * @param mixed $value - value field
	 * @since 0.16.0
	 */
	public function setValue($value){
		$this->setTag('value',$value);
	}

	/**
	 * Get value field
	 *
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
		return '<label for="'.$this->getId().'">'.htmlspecialchars($this->getLabel()).'</label>';
	}

    /**
     * {@inheritdoc}
     */
	public function componentRender(){
		$template='<input ';
		foreach($this->getTags() as $kTag=>$tag){
			if($tag!='')
				$template.=$kTag.'="'.htmlspecialchars($tag).'" ';
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