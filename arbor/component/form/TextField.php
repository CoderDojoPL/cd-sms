<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;

/**
 * @since 0.15.0
 */
class TextField extends InputField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		$options['type']='text';

		if(!isset($options['validator'])){
			$options['validator']='Arbor\Validator\Text'.(!isset($options['required']) || !$options['required']?'OrEmpty':'');
		}

		parent::__construct($options);
	}

}