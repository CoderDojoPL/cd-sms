<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;

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
			$options['validator']='Arbor\Validator\Number'.(!isset($options['required']) || !$options['required']?'OrEmpty':'');
		}

		parent::__construct($options);
	}

}