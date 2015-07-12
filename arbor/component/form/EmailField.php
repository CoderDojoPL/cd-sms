<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;
use Arbor\Validator\EmailValidator;

/**
 * @since 0.17.0
 */
class EmailField extends InputField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		$options['type']='email';

		if(!isset($options['validator'])){
			$this->setValidator(new EmailValidator());
		}

		parent::__construct($options);

	}

}