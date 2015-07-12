<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;
use Arbor\Validator\NumberValidator;

/**
 * @since 0.16.0
 */
class NumberField extends InputField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		$options['type']='number';

		if(!isset($options['validator'])){
			$this->setValidator(new NumberValidator());
		}

		if(isset($options['min'])){
			$this->setMin($options['min']);
			unset($options['min']);
		}

		if(isset($options['max'])){
			$this->setMax($options['max']);
			unset($options['max']);
		}

		parent::__construct($options);
	}

	/**
	 * set html tag min
	 * @param integer $value - value of tag min
	 * @since 0.18.0
	 */
	public function setMin($value){
		$this->setTag('min',$value);
		if($this->getValidator()){
			$this->getValidator()->setOption('min',$value);
		}
	}

	/**
	 * get html tag min
	 * @return integer - value of tag min
	 * @since 0.18.0
	 */
	public function getMin(){
		return $this->getTag('min');
	}

	/**
	 * set html tag max
	 * @param integer $value - value of tag max
	 * @since 0.18.0
	 */
	public function setMax($value){
		$this->setTag('max',$value);
		if($this->getValidator()){
			$this->getValidator()->setOption('max',$value);
		}
	}

	/**
	 * get html tag max
	 * @return integer - value of tag max
	 * @since 0.18.0
	 */
	public function getMax(){
		return $this->getTag('max');
	}

}