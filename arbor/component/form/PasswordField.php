<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\InputField;

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
			$options['validator']='Arbor\Validator\Text'.(!isset($options['required']) || !$options['required']?'OrEmpty':'');
		}

		parent::__construct($options);

	}

}