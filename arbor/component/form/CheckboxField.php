<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;

/**
 * @since 0.15.0
 */
class CheckboxField extends InputField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		$options+=array(
			'checked'=>false
		);

		$options['type']='checkbox';

		if(!isset($options['validator'])){
			$options['validator']='Arbor\Validator\Boolean'.(isset($options['required']) && $options['required']?'OrEmpty':'');
		}

		parent::__construct($options);

	}

    /**
     * {@inheritdoc}
     */
	public function setData($value){
		$this->setChecked((boolean)$value);
	}

    /**
     * {@inheritdoc}
     */
	public function getData(){
		if($this->isChecked()){
			if($this->getValue())
				return $this->getValue();
			else
				return 'on';
		}
		else
			return false;
	}

	/**
	 * get value of tag checked
	 * @return boolean
	 * @since 0.15.0
	 */
	public function isChecked(){
		return $this->getTag('checked');
	}

	/**
	 * set value of tag checked
	 * @param boolean $flag - if true then checked else unchecked
	 * @since 0.15.0
	 */
	public function setChecked($flag){
		return $this->setTag('checked',$flag);
	}

}