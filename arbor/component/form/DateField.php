<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;
use Arbor\Validator\DateValidator;

/**
 * @since 0.16.0
 */
class DateField extends InputField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		$options['type']='date';

		if(!isset($options['validator'])){
			$this->setValidator(new DateValidator());
		}

		parent::__construct($options);

	}

}