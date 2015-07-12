<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\InputField;
use Arbor\Validator\TextValidator;

/**
 * @since 0.17.0
 */
class PasswordField extends InputField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		$options['type']='password';

		if(!isset($options['validator'])){
			$this->setValidator(new TextValidator());
		}

		parent::__construct($options);

	}

}