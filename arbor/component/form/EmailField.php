<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;

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
			$options['validator']='Arbor\Validator\Email'.(!isset($options['required']) || !$options['required']?'OrEmpty':'');
		}

		parent::__construct($options);

	}

}